<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Entity\Prime;
use App\Entity\SportEvent;
//use App\Entity\Seat;
//use App\Entity\SeatPrime;
use App\Entity\Ticket;
use App\Entity\TicketPrice;
//use App\Entity\TicketPerson;
use App\Entity\XRate;
use BaconQrCode\Encoder\QrCode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\QrCode as QrCodeQrCode;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;

class TicketController extends AbstractController
{
    private $qrBuilder;
    private $serializer;
    private $texter;
public function __construct(BuilderInterface $qrBuilder, SerializerInterface $serializer, TexterInterface $texter)
{
    $this->qrBuilder = $qrBuilder;
    $this->serializer  = $serializer;
    $this->texter = $texter;
}

    /**
     * @Route("/", name="home")
     */
    public function index(Request $request): Response
    {
        $rate = $this->getDoctrine()->getRepository(XRate::class)->find(1);
        
        //$person = new TicketPerson();
        
        $entityManager = $this->getDoctrine()->getManager();
        if($request->getMethod() == "POST"){
            
            $muser = $this->getUser();
            $price_id = $request->request->get('price');
            
            //$currency = $request->request->get('currency');
            $event_id = $request->request->get('event_id');
            $quantity = $request->request->get('quantity');

            $event = $this->getDoctrine()->getRepository(SportEvent::class)->find($event_id);
            
            $price = $this->getDoctrine()->getRepository(TicketPrice::class)
            ->findOneBy(["id"=>$price_id,"sportEvent"=>$event_id]);
            //die(var_dump($price_id));
            //$this->getAvailableSeat($price->getType());
            
            //
           
            $total = $quantity * $price->getPrice();
            $payment = new Payment();
            $payment->setAmount($total);
            $payment->setCurrency("USD");
            $payment->setQuantity($quantity);
            $payment->setUser($muser);
            $payment->setReference($this->getReference());
            $payment->setIsApproved(false);
            $entityManager->persist($payment);
            $entityManager->flush();
            
            $request->getSession()->set("payment",$payment);
            $request->getSession()->set("price_id",$price_id);

           return $this->redirectToRoute("payment");
            //return $this->redirectToRoute("ticketpdf",["id"=>$person->getId()]);

        }
        $allPrices = $this->getDoctrine()->getRepository(TicketPrice::class)->findAll();
        $allEvents = $this->getDoctrine()->getRepository(SportEvent::class)->findAll();
        //$seatsEc = $this->countAvailableSeat("ECONOMIC");
        //$seatVi = $this->countAvailableSeat("VIP");
        /*$primes_json = $this->serializer ->serialize(
            $allPrimes,
            'json', null
        );*/

        return $this->render('ticket/index.html.twig', [
            'prices' => $allPrices,
            'events'=>$allEvents,
            //'primes_json'=>$primes_json,
            'rate'=>$rate,
            //'seats_ec'=>$seatsEc,
            //'seats_vi'=>$seatVi
        ]
    );
    }

   /* private function addSeatPrice(Seat $seat, Prime $prime){
        $st_pr = new SeatPrime();
        $st_pr->setPrime($prime);
        $st_pr->setSeat($seat);

        $this->getDoctrine()->getManager()->persist($seat);
        $this->getDoctrine()->getManager()->flush();
    }*/

    private function generateCode(){
        $tkn = "";
        $flag = true;
        while($flag){
            $tkn = bin2hex(random_bytes(60));
            $tckt = $this->getDoctrine()->getRepository(Ticket::class)->findOneBy(["code"=>$tkn]);
            if(!$tckt){
                break;
            }

        }
        return $tkn;
    }
    
    private function sendSMS($code,$number,$phone){
        //send sms with validation code
        $sms = new SmsMessage(
            // the phone number to send the SMS message to
            '+243896035491',
            // the message
            $code.' is your validation code for your ticket'
        );
        $sentMessage = $this->texter->send($sms);
        //die(var_dump($sentMessage));

    }

   /* private function getAvailableSeat($type) : ?Seat{
        //die($type);
        $seat = $this->getDoctrine()->getManager()->createQuery( "select u from App\Entity\Seat u where u.type = '{$type}' and u.id not in (select IDENTITY(s.seat) from App\Entity\SeatPrime s)")
    ->setMaxResults(1);
        $founds = $seat->getResult(); 
        //die(var_dump($seat->getResult()));
        
        return $founds[0];
    }*/
    /*private function countAvailableSeat($type) : int{
        $seat = $this->getDoctrine()->getManager()->createQuery("select COUNT(u) from App\Entity\Seat u where u.type = '{$type}' and u.id not in 
        (select s.id from App\Entity\SeatPrime s)");
        $founds = $seat->getResult(); 
        //die(var_dump($seat->getResult()));
        //return $founds[0];
        return $founds[0][1];
    }*/

   /* private function chekSeat(Seat $seat,Prime $prime){

    //$price = $prime->getTicketPrices();
    $seatprime = 
    $this->getDoctrine()->getRepository(SeatPrime::class)->
    findOneBy(["seat"=>$seat->getId(),"prime"=>$prime->getId()]);

    if($seatprime != null){
        return true;
    }

    return false;


    }*/
    /*private function generateValidation(){
        $tkn = "";
        $flag = true;
        while($flag){
            $tkn = bin2hex(random_bytes(3));

            $tckt = $this->getDoctrine()->getRepository(TicketPerson::class)->findOneBy(["validationCode"=>$tkn]);
            if(!$tckt){
                break;
            }

        }
        $tkn = strtoupper($tkn);
        return $tkn;
    }*/

    /**
     * @Route("/ticket/{id}",name="ticketpdf")
     */
    public function ticketPdf($id){
        $payment = $this->getDoctrine()->getRepository(Payment::class)->find($id);
        $tickets = $payment->getTickets();

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);
        
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        //$dompdf->setPaper(array(0,0,600,8000));
       /* $GLOBALS['bodyHeight'] = 0;
        $dompdf->setCallbacks(
            array(
                'myCallbacks' => array(
                  'event' => 'end_frame', 'f' => function ($infos) {
                    $frame = $infos["frame"];
                    if (strtolower($frame->get_node()->nodeName) === "body") {
                        $padding_box = $frame->get_content_box();
                        $GLOBALS['bodyHeight'] += $padding_box['h'];
                    }
                  }
                )
              )
                );*/
        //$qrCode = new QrCode("this is a code");
        //$qrcode = new QrCodeQrCode('this is qr code');
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('ticket/ticket.html.twig', [
            'tickets' => $tickets
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $hgt = 350 * $payment->getQuantity() - 100;
        $dompdf->setPaper(array(0,0,200,$hgt));

        // Render the HTML as PDF
        $dompdf->render();
        //unset($dompdf);

        //$dompdf = new Dompdf();
        //$dompdf->setPaper(array(0,0,600,));
        //$dompdf->loadHtml($html);
        //$dompdf->render();
        //$dompdf->stream();
        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);
    }
    
    /**
     * @Route("/api/validate", name="validate_code")
     */
    public function validator(Request $request)
    {
        if($request->getMethod() == "POST")
        {
            $return_arr = ["validate"=>0,"description"=>""];
            
            $arrRequest = json_decode($request->getContent());
            $code = $arrRequest->code;
            $validatorCode = $arrRequest->validator;
            //die(print_r($arrRequest));
            $em = $this->getDoctrine()->getManager();
            $ticket = $em->getRepository(Ticket::class)->findOneBy(["code"=>$code]);
            if($ticket != null){
                $prime = $ticket->getPrices()->getPrime();
                $prime_curr = $this->getDoctrine()->getRepository(Prime::class)->findBy(["isCurrent"=>true]);
                if($prime->getIsCurrent() == false){
                    $return_arr["validate"] = 0;
                    $return_arr["description"] = "Billet n'est pas pour ce Prime";
                    $json = json_encode($return_arr);
                    return new JsonResponse($json,200,[],true);
                    exit;

                }
                else if($ticket->getIsValidated()){
                    $return_arr["validate"] = 0;
                    $return_arr["description"] = "Billet deja validé!";
                    $json = json_encode($return_arr);
                    return new JsonResponse($json,200,[],true);
                    exit;

                }
                $person = $ticket->getPerson();
                if($ticket->getPerson()->getValidationCode()  == $validatorCode){
                    $ticket->setIsValidated(true);
                    $em->flush();

                    $return_arr["validate"] = 1;
                    $return_arr["description"] = "success";
                    $json = json_encode($return_arr);
                    return new JsonResponse($json,200,[],true);
                    exit;
                }
                else{
                    $return_arr["description"] = "Code n'est pas valide!";
                    $json = json_encode($return_arr);
                    return new JsonResponse($json,200,[],true);
                    exit;

                }
            }
            $return_arr["description"] = "Billet n'a pas été trouvé!";
            $json = json_encode($return_arr);
            return new JsonResponse($json,200,[],true);


        }

        return $this->redirectToRoute('home');

    }

    /**
     * @Route("/api/ticket/{code}",name="find_ticket")
     */
    public function findTicket($code){                      
        $ticket = $this->getDoctrine()->getRepository(Ticket::class)->findOneBy(["code"=>$code]);
        $arrayResponse = [];
        if($ticket != null){
            $person = $ticket->getPerson();
            $dt = $person->getCreationTime();
            //die(print_r($dt->format('d-m-Y H:i:s')));
            $arrayResponse = [
                "result"=>1,
                "ticketType"=>$ticket->getPrices()->getType(),
                "name"=>$person->getFullName(),
                "phone"=>$person->getPhoneNumber(),
                "creationTime"=>$person->getCreationTime()->format('d-m-Y H:i:s'),
                "validated"=>$ticket->getIsValidated()    
            ];

        }
        else{
            $arrayResponse = ["result"=>0,"message"=>"No matching data found!"];
        }
        $json = json_encode($arrayResponse);

        return new JsonResponse($json,200,[],true);
        
    }
    private function getReference(){
        $old = strtotime(date('Y-m-d h:i:s', strtotime('1970-01-01 10:00:00')));
        $now = strtotime(date('Y-m-d h:i:s'));
        $dif = $now - $old;
        return $dif;
    }
   
}
