# Guhemba payment package

A laravel-php package that facilitates the integration of guhemba payment api in your application

# [Official Documentation](https://yupidoc.com/projects/guhemba-webelement/preview)

From the version `>=3.0.0` , You can find the official [documentation here](https://yupidoc.com/projects/guhemba-webelement/preview)

<br>
<br>
<br>
<br>
<br>

# OLD VERSIONS DOCUMENTATION(<=v2.1.2)

At this page you will see the documentation of this package form v0 - v2.1.2

## 1. Prerequisite

-   laravel framework

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

After running this command you should see a `guhemba-webelement` file under the directory `config`, then you will need to provide all information required in that file.

### 3.2 Configuration for close partners

If you don't have a guhemba partner key, then you should skip this section.
Otherwise Make sure that you put the bellow code on top of all your requests:

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

Now at this stage, I really feel like you are ready to go. let's enjoy the package now 😎.

## 4. Generating a payment Qrcode

To generate a payment qrcode, all what you need is to place the bellow script in your code

```php
    $amount = 1000;

    $paymentReference = 'order_id';

    $confirmPaymentKey = 'Unique_key';

    $qrcode = Guhemba::generateQrcode(
        $amount,
        $paymentReference,
        $confirmPaymentKey
    )->getQrcode();
```

Note: when you are expecting guhemba to send you a feedback when a transaction is done, then you should send the
`$paymentReference` when generating a Qrcode, this is the reference of the product or group of products that your customer is buying. But also you need to provide a `payment_confirmation_endpoint` in your wallet settings on Guhemba. this endpoint must accept `POST` request. The endpoint will be hitted when the transaction is completed and it's will contain the following response:

```php
    [
        'payment_reference' => 'Your-provided-payment-ref',
        'transaction_token' => 'string',
        'confirm_payment_key' => 'string'
    ]
```

The `confirm_payment_key` will help you to secure your provided `payment_confirmation_endpoint`, You shoud keep it safe After generating the qrcode because it is the unique key that will help you to check if the request is coming from guhemba.

## 5. Redirect user to guhemba

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

By default this method will redirect user where he can choose a payment option(card,mtn,...). means if you already know the method that a user will pay with, you can pass the `paymentOption` to the redirect method:

```php
    function redirectToGuhemba()
    {
        $qrcodeSlug ='91da-5a565f0b173c';
        $paymentRef = 6;
        $paymentOption = "card";// can be:  mtn,card or choice(the default)

        return Guhemba::redirect($qrcodeSlug, $paymentRef,$paymentOption)
    }
```

The `slug` of the qrcode, you will get it after generating a qrcode and The `paymentRef` is the reference of the order that your customer want to pay, this reference may help you to know which product your customer has paid after accomplishing his payment on guhemba.

`Note` : Please make sure all information are well set in the `config file` of guhemba

If you need to check the formated full url before your customer get redirected to guhemba, we give you the ability to dump
the url

```php
    function redirectToGuhemba()
    {
        $qrcodeSlug ='91da-5a565f0b173c';
        $paymentRef = 6;

        return Guhemba::dump()->redirect($qrcodeSlug, $paymentRef)
    }
```

But also the package provide another alternative for checking the redirection full url by logging it:

```php
    function redirectToGuhemba()
    {
        $qrcodeSlug ='91da-5a565f0b173c';
        $paymentRef = 6;

        return Guhemba::shouldLog()->redirect($qrcodeSlug, $paymentRef)
    }
```

## 6. Fetch transaction Info

You can fetch a transaction data in different ways:

### 6.1 Get transaction Info from a callback

When the user completes the payment on guhemba, he will be redirected back to your system using the value of `GUHEMBA_REDIRECT_URL` that you have set in the config file.

Now to grab the transaction information that he has performed use this script:

```php
    function guhembaCallback()
    {
        $transaction = Guhemba::transaction()->getTransaction();
    }
```

For stateless, you can get the transaction in the following way

```php
    function guhembaCallback()
    {
        $transaction = Guhemba::stateless()->transaction()->getTransaction();
    }
```

This time An extra field: `reference` will be added on the transaction `object`.

### 6.2 Get transaction Info using transaction token

It may happen that you need to check if a payment transaction Having a given token exits in your merchant wallet on guhemba, to do that you only need the bellow script:

```php
  $token = 'S-7578987654';

  $transaction = Guhemba::transactionFromToken(
                    $token
                )->getTransaction();

```

### 6.3 Get transaction Info using a payment reference

You may need to check what happened to the transaction that you have initiated yet you don't have the transaction token, the package provides a way to check using the payment reference that you have provided when generating the qrcode

```php
    $paymentRef = "2";
    $paymentConfirmKey = "09876545";
    $response = Guhemba::transactionFromPaymentReference(
        $paymentRef,
        $paymentConfirmKey
    );

    if (!$response->isOk()) dd($response->getMessage());

    $transaction = $response->getTransaction();
```

`Note`: the `$paymentConfirmKey` is optional but it is very important for fetching result with precision in case you are using your merchant wallet on different e-commerce

### 6.3 Get transaction Info using a Qrcode Id

You can also check if a transaction exists using the payment qrcode id that you have generated

```php
    $qrcodeId = 4;
    $response = Guhemba::transactionFromQrcode(
        $qrcodeId
    );

    if (!$response->isOk()) dd($response->getMessage());

    $transaction = $response->getTransaction();
```

## 7. Other methods that you need to use specially for Error handling

### 7.1 getResponse()

You can call this method on all requests except the `redirect` method. For example you want to generate a qrcode:

```php
    $amount = 1000;
    $response = Guhemba::generateQrcode($amount)->getResponse();
```

The above script will give you the object that contains all properties of the response

### 7.1 isOk() and getMessage()

To check if the request was successfully done you can use the `isOk` method to avoid bugs in your system. and also it may happen that the request was not successfully performed, at that time you will need to use the method `getMessage()`

```php
    $amount = 1000;
    $generateQrcode = Guhemba::generateQrcode($amount);

    if (! $generateQrcode->isOk()) return $generateQrcode->getMessage();

    $qrcode = $generateQrcode->getQrcode();
```

`Note`: The method `isOkay` is a boolean and `getMessage()` returns a string.

<br/>
Enjoy guys.
