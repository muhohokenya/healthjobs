<?php




$validator->after(function ($validator) use ($request) {
    $practitionerLicence = $request->only(['licence_number']);

    $licence_number = $request->only('licence_number')['licence_number'];

    if ($licence_number!==null) {
        $verification = $this->verificationService->verifyPractitioner($practitionerLicence['licence_number'],
            $request,false
        );


        if ($verification['success'] === false) {
            $validator->errors()->add(
                'licence_number',
                'No matching facility found in the registry with the Licence Number '.$request->licence_number
            );
        }
    }



});
$validated = $validator->validate();

$licence_number = $validated['licence_number'];


if ($licence_number!==null) {
    $verification = $this->verificationService->verifyPractitioner($licence_number,$request,false);

    $payload['licence_number'] = $verification['data']['licence_number'];
    $payload['name'] = $verification['data']['name'];
    $payload['expiry_date'] = $verification['data']['expiry_date'];


    if ($verification['success'] === false) {
        dd("I am just an intern I don't have a licence number ");
        $name = $validated['name'];
        $licence_number = '';// We assume you don't have a licence yet
    }else{
        $name = $validated['name'];
        $licence_number = $verification['data']['licence_number'];
    }
}else{

    $payload['licence_number'] = '';
    $payload['name'] = $validated['name'];
    $payload['expiry_date'] = '';

}
