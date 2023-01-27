<?php
/* The following file uses mollie, and specifically its test api to process mock
 * iDeal payments. It's API reference can be found here:
 * <https://docs.mollie.com/reference/v2/payments-api/overview>
 */

function make_payment($price) {
    include_once "include/mollie.php";

    $price_str = number_format($price, 2, '.', '');

    $url = "https://api.mollie.com/v2/payments";

    // Unfortunately mollie does not support localhost as a redirect url, so our
    // actual URL must also be used during testing.
    $data = [
        'amount' => ['currency' => 'EUR', 'value' => $price_str],
        'description' => 'Test payment',
        'redirectUrl' => 'https://webtech-in21.webtech-uva.nl/purchase.php?result=success',
        'cancelUrl' => 'https://webtech-in21.webtech-uva.nl/purchase.php?result=canceled',
        'method' => 'ideal'
    ];

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        "Content-type: application/json",
        // "Authorization: Bearer $mollie_token"
    ]);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

    $json_response = curl_exec($curl);
    $response = json_decode($json_response, true);

    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ( $status != 201 ) {
        // reload_err("Error sending request to payment processor.");
        return false;
    }

    curl_close($curl);

    var_dump($response);

    // Exit gracefully in case of unexpected response.
    try {
        $checkout_url = $response["_links"]["checkout"]["href"];
        $mollie_id = $response["id"];
    } catch (Exception $_) {
        // reload_err("Error sending request to payment processor");
        return false;
    }
    header("Location: " . $checkout_url);

    return $mollie_id;
}

function payment_status($id) {
    include_once "include/mollie.php";

    $url = "https://api.mollie.com/v2/payments/$id";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $mollie_token"
    ]);

    $json_response = curl_exec($curl);
    $response = json_decode($json_response);
    $status = curl_getinfo($json_response);

    // TODO: Do proper error handling.
    // TODO: 201 is not really expected for a get request (?)
    if ( $status != 201 ) {
        die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
    }

    curl_close($curl);

    // TODO: Wrap in try/catch in case of unexpected response.
    $status = $response["status"];

    // TODO: Set correct status in database.
}
