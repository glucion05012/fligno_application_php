<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PayPal\Api\Item;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Payment;
use PayPal\Api\ItemList;
use PayPal\Api\WebProfile;
use PayPal\Api\InputFields;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\PaymentExecution;

class PaypalController extends Controller
{
    public function create(){
        $apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(
            'ASAK6qXQBsucM-T9ZTUksgJUnXUguKkZBHEGj6_f_VGsAy-tYxiEO19OYDpXKQw9hAjpYsh1qznICtp1',     // ClientID
            'EASm-jjPfH7pAh5XCDHNtqIjNJ9_Zujfn-Faun0_LbDEfMTKEfBa-XoLNSVBVf0JFH9RKpQcOXIsM0AI'      // ClientSecret
        )
    );

    $payer = new Payer();
    $payer->setPaymentMethod("paypal");

    $item1 = new Item();
    $item1->setName('Profile User Subscription')
        ->setCurrency('USD')
        ->setQuantity(1)
        ->setSku("123123") // Similar to `item_number` in Classic API
        ->setPrice(7.5);
    $item2 = new Item();
    $item2->setName('Service Charge')
        ->setCurrency('USD')
        ->setQuantity(5)
        ->setSku("321321") // Similar to `item_number` in Classic API
        ->setPrice(2);

    $itemList = new ItemList();
    $itemList->setItems(array($item1, $item2));

    $details = new Details();
    $details->setShipping(1.2)
        ->setTax(1.3)
        ->setSubtotal(17.50);

    $amount = new Amount();
    $amount->setCurrency("USD")
        ->setTotal(20)
        ->setDetails($details);

    $transaction = new Transaction();
    $transaction->setAmount($amount)
        ->setItemList($itemList)
        ->setDescription("Payment description")
        ->setInvoiceNumber(uniqid());

    $redirectUrls = new RedirectUrls();
    $redirectUrls->setReturnUrl("http://laravel-paypal-example.test")
        ->setCancelUrl("http://laravel-paypal-example.test");

    // Add NO SHIPPING OPTION
    $inputFields = new InputFields();
    $inputFields->setNoShipping(1);

    $webProfile = new WebProfile();
    $webProfile->setName('test' . uniqid())->setInputFields($inputFields);

    $webProfileId = $webProfile->create($apiContext)->getId();

    $payment = new Payment();
    $payment->setExperienceProfileId($webProfileId); // no shipping
    $payment->setIntent("sale")
        ->setPayer($payer)
        ->setRedirectUrls($redirectUrls)
        ->setTransactions(array($transaction));

    try {
        $payment->create($apiContext);
    } catch (Exception $ex) {
        echo $ex;
        exit(1);
    }

    return $payment;
    }

    public function execute(Request $request){
        $apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(
            'ASAK6qXQBsucM-T9ZTUksgJUnXUguKkZBHEGj6_f_VGsAy-tYxiEO19OYDpXKQw9hAjpYsh1qznICtp1',     // ClientID
            'EASm-jjPfH7pAh5XCDHNtqIjNJ9_Zujfn-Faun0_LbDEfMTKEfBa-XoLNSVBVf0JFH9RKpQcOXIsM0AI'      // ClientSecret
        )
    );

    $paymentId = $request->paymentID;
    $payment = Payment::get($paymentId, $apiContext);

    $execution = new PaymentExecution();
    $execution->setPayerId($request->payerID);

    // $transaction = new Transaction();
    // $amount = new Amount();
    // $details = new Details();

    // $details->setShipping(2.2)
    //     ->setTax(1.3)
    //     ->setSubtotal(17.50);

    // $amount->setCurrency('USD');
    // $amount->setTotal(21);
    // $amount->setDetails($details);
    // $transaction->setAmount($amount);

    // $execution->addTransaction($transaction);

    try {
        $result = $payment->execute($execution, $apiContext);
    } catch (Exception $ex) {
        echo $ex;
        exit(1);
    }

    return $result;
        
    }
}
