<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\TicketPrice;
use App\Entity\Ticket;
use App\Entity\Payment;
//use App\Entity\Seat;
//use App\Entity\SeatPrime;
//use App\Entity\TicketPerson;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;


class PaymentController extends AbstractController
{
    private $session;
    private $texter;
    public function __construct(SessionInterface $session, TexterInterface $texter){
        $this->session = $session;

    }
    /**
     * @Route("/payment", name="payment")
     */
    public function index(): Response
    {
        //$ticketId = $this->session->get('ticketId');
        $payment_order = $this->session->get('payment');
        //$ticket = $this->getDoctrine()->getRepository(Ticket::class)->find($ticketId);
        $person = $this->getUser();
        //$price = $ticket->getPrices();
        //$total_price = $this->session->get("total");
        

                $pageDescription = "Payez pour confirmer votre Ticket";
                $fullname=$person->getNames();
                $phone  = $person->getPhone();
                //$email = $user->getEmail();
                $reference= $payment_order->getReference();
                $currency =  $payment_order->getCurrency();
                $amount =  $payment_order->getAmount();
                $merchant = "54A8CE7E-DD30-4486-8095-1B723C8FA622";
                $usdUrl = "https://merchant.arakapay.com/payment/54A8CE7E-DD30-4486-8095-1B723C8FA622";
                $this->session->set('payment_reference',$reference);
        /*if($currency == "CDF"){
            $usdUrl = "https://merchant.arakapay.com/payment/6F96D336-BDF5-4227-BE80-F16C1F31FF30";
        }*/

        /*$url = $usdUrl."?pageDescription=".$pageDescription."&customerFullName=".$fullname.
                "&customerPhoneNumber=".$phone."&transactionReference=".
                $reference."&currency=".$currency."&amount=".$amount."&merchant=".$merchant;
        header("location:".$url);*/

        return $this->redirectToRoute("payment_complete");
    }

    public function generateReference(){
        $tkn = "";
        $flag = true;
        while($flag){
            $tkn = bin2hex(random_bytes(4));

            $tckt = $this->getDoctrine()->getRepository(Payment::class)->findOneBy(["reference"=>$tkn]);
            if(!$tckt){
             break;
            }

        }
        $tkn = strtoupper($tkn);
        return $tkn;

    }

    /**
     * @Route("/payment/complete", name="payment_complete")
     */
    public function paymentComplete(Request $request){
        if(!$this->session->has('payment_reference')){
            return $this->redirectToRoute('home');
        }
        $em = $this->getDoctrine()->getManager();
        //$trans_status = $request->query->get('transactionStatus');
        $trans_status = 'APPROVED';
        //$sysRef  =  $request->query->get('systemReference');
        //$ticketId = $this->session->get('ticketId');
        
        //$payment->setPerson($person);
        $payment = $em->getRepository(Payment::class)->find($this->session->get('payment')->getId());
        $person = $this->getUser();
        $price = $em->getRepository(TicketPrice::class)->find($this->session->get('price_id'));

        if($trans_status == 'APPROVED')
        {
            $payment->setIsApproved(true);
           
            
            for($i=1;$i<=$payment->getQuantity(); $i++){
                $ticket = new Ticket();
                $code = $this->generateCode();
                $ticket->setCode($code);
                $ticket->setIsValidated(false);
                $ticket->setPrices($price);   
                $ticket->setPayment($payment);        
                //$ticket->setPerson($person);
                //$st = $this->getAvailableSeat($price->getType());
                $em->persist($ticket);
                

            }
            $em->flush();


            //$this->sendSMS($person->getValidationCode(),$payment->getQuantity(),$person->getPhone());
            
        }
        
        //$em->persist($payment);
        //$em->flush();        
        //$tickets = $person->getTickets();
        $this->session->remove('ticket_id');
        $this->session->remove('personId');
        $this->session->remove('currency');
        $this->session->remove('priceId');
        $this->session->remove('primeId');
        $this->session->remove('total');
        $this->session->remove('payment_reference');
        
        
        if($trans_status != 'APPROVED'){
            $this->session->set('payment',"canceled");
            $this->addFlash("danger","Desole! Votre paiement a été annulé, merci de recommencer.");
            return $this->redirectToRoute('home',['payment'=>"payment_cancel"]);
        }

        return $this->redirectToRoute("ticketpdf",["id"=>$payment->getId()]);

    }
    
    private function sendSMS($code,$number,$phone){
        //send sms with validation code
        $chk = "votre";
        $tk = 'billet';
        if($number > 1){
            $chk = "vos";
            $chk =  "billets";
        }
        $sms = new SmsMessage(
            // the phone number to send the SMS message to
            $phone,
            // the message
            $code.' est votre code de validation de '.$chk.' '.$tk
        );
        $sentMessage = $this->texter->send($sms);
        //die(var_dump($sentMessage));

    }
   
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
    /*private function getAvailableSeat($type) : ?Seat{
        //die($type);
        $seat = $this->getDoctrine()->getManager()->createQuery( "select u from App\Entity\Seat u where u.type = '{$type}' and u.id not in (select IDENTITY(s.seat) from App\Entity\SeatPrime s)")
    ->setMaxResults(1);
        $founds = $seat->getResult(); 
        //die(var_dump($seat->getResult()));
        
        return $founds[0];
    }*/
}
