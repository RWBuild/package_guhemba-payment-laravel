# Guhemba payment package
A laravel-php package that facilitates the integration of guhemba payment in your application 

## 1. Prerequisite
- laravel framework

## 2. Installation
```
 composer require rwbuild/guhemba-web-element
```

## 3. Configuration

### 3.1 Publish config file

This configuration concerns systems that use a single merchant wallet for receiving payment,
In order to start enjoying the package, you will need to publish the config file that support `Guhemba` 

```
 php artisan vendor:publish --tag=config 
```

After running this command you should see a `guhemba-webelement` file  under the directory `config`, then you will need to provide all information required in that file.

### 3.2 Configuration for close partners

Make sure that you put the bellow code on top of all your requests:

```php
    \RWBuild\Guhemba\Facades\Guhemba::partnerKeys([
        'GUHEMBA_PUBLIC_PARTNER_KEY' => 'request-this-on-guhemba',
        'GUHEMBA_PARTNER_KEY' => 'request-this-on-guhemba',
        'GUHEMBA_BASE_URL' => 'guhemba-base-url'// Ask guhemba support team
    ]);
```

I advice you to put the above code in one of your service providers class in `boot` method,
Then you should provide the bellow information on each request that you are performing:

```php
    Guhemba::dynamicMerchant([
        'GUHEMBA_API_KEY' => 'wallet-merchant-integration-api-key',
        
        'GUHEMBA_MERCHANT_KEY' => 'wallet-merchant-key',

        'GUHEMBA_PUBLIC_KEY' => 'wallet-merchant-integration-public-key',

        'GUHEMBA_REDIRECT_URL' => 'your-dynamic-url'
    ]);
```
All these information above, you can find them in guhemba merchant wallet under the integration menu in settings Or you can request for them programatically.

🤪 Now at this stage, I really feel that you are ready to go. let's enjoy the beauty of the package now 😎.

## 4. Generating a payment Qrcode

To generate a payment qrcode, all what you need is to place the bellow script in your code

```php
  $amount = 1000;

  $paymentReference = null; 

  $qrcode = Guhemba::generateQrcode($amount, $paymentReference)->getQrcode();
```

Note: when you are expecting guhemba to send you a feedback when a transaction is done, then you should send the
      `$paymentReference` when gerating a Qrcode. But also you need to provide a `payment_confirmation_endpoint` in your wallet settings. this endpoint must accept `POST` request. 

      The endpoint will be hitted when the transaction is completed and it's will contain the following response:

      ```php
        [
            'payment_reference' => 'Your-provided-payment-ref',
            'transaction_token' => 'string'
        ]
      ``` 

## 5. Get transaction Info using transaction token

It may happen that you need to check if a payment transaction Having a given token exits in your merchant wallet on guhemba, to do that you only need the script bellow:

```php
  $token = 'S-7578987654';

  $transaction = Guhemba::transactionFromToken(
                    $token
                )->getTransaction();

```

## 6. Redirect user to guhemba

As guhemba payment gives a good `user interface` where the `qrcode` will appear so that user can scan it or can decide to hit the `pay` button for completing the payment on guhemba.

Let's say, user decides to complete the payment on guhemba web then he hits the `pay` button, you will need to redirect him on guhemba. To do that, just have a look:

```php
    function redirectToGuhemba()
    {
        $qrcodeSlug ='91da-5a565f0b173c';
        $paymentRef = 6;

        return Guhemba::redirect($qrcodeSlug, $paymentRef)
    }
```

The `slug` of the qrcode, you will get it after generating a qrcode and The `paymentRef` is the reference of the order that your customer want to pay, this reference may help you to know which product your customer has paid after accomplishing his payment on guhemba.

`Note` : Please make sure all information are well set in the `config file` of guhemba

## 7. Get transaction Info from a callback

When the user completes the payment on guhemba, he will be redirected back to your system using the value of `GUHEMBA_REDIRECT_URL` that you have set in the config file.

Now to grab the transaction information that he has performed use this script:

```php
    function guhembaCallback()
    {
        $transaction = Guhemba::transaction()->getTransaction();
    }
```

This time An extra field: `reference` will be added on the transaction `object`.

## 8. Other methods that you  need to use specially for Error handling

### 8.1 getResponse()

You can call this method on all requests except the `redirect` method. For example you want to generate a qrcode:

```php
    $amount = 1000;
    $response = Guhemba::generateQrcode($amount)->getResponse();
```

The above script will give you the object that contains all properties of the response 

### 8.1 isOk() and getMessage()

To check if the request was successfully done you can use the `isOk` method to avoid bugs in your system. and also it may happen that the request was not successfully performed, at that time you will need to use the method `getMessage()` 

```php
    $amount = 1000;
    $generateQrcode = Guhemba::generateQrcode($amount);

    if (! $generateQrcode->isOk()) return $generateQrcode->getMessage();

    $qrcode = $generateQrcode->getQrcode();
```

`Note`: The method `isOkay`, it's a boolean and `getMessage()` return a string.


<br/>
Enjoy guys.