<?php
$profileLink = $_GET['profileLink'];

$doc = new DOMDocument();
@$doc->loadHTMLFile($profileLink);

if ($doc === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch data from Topmate.']);
    exit();
}

$creatorNameElement = $doc->getElementById("profileUsername");
$creatorName = $creatorNameElement ? $creatorNameElement->textContent : '';

$creatorAboutElement = $doc->getElementById("profileAbout");
$creatorAbout = $creatorAboutElement ? $creatorAboutElement->textContent : '';

$servicesContainer = $doc->getElementById("serviceContainer");
$services = $servicesContainer ? $servicesContainer->getElementsByTagName("li") : [];

$servicesData = [];
foreach ($services as $service) {
    $priceElement = $service->getElementsByTagName("span")->item(0);
    $price = $priceElement ? $priceElement->textContent : '';

    $serviceNameElement = $service->getElementsByTagName("h3")->item(0);
    $serviceName = $serviceNameElement ? $serviceNameElement->textContent : '';

    $serviceData = [
        'price' => $price,
        'serviceName' => $serviceName
    ];

    $servicesData[] = $serviceData;
}

$responseData = [
    'creatorName' => $creatorName,
    'creatorAbout' => $creatorAbout,
    'services' => $servicesData
];

header('Content-Type: application/json');

echo json_encode($responseData);
?>
