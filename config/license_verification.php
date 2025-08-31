<?php

return [
    // External verification service URLs
    'poisons_board_url' => env('POISONS_BOARD_URL', 'https://practice.pharmacyboardkenya.org/ajax/public'),
    'nck_url' => env('NCK_URL', 'https://osp.nckenya.com/ajax/public'),
    'coc_url' => env('COC_URL', 'https://portal.clinicalofficerscouncil.org/ajax/public'),
    'kmpdu_url' => env('KMPDU_URL', 'https://kmpdc.go.ke/Registers/General_Practitioners.php'),
    'kmlttb_url' => env('KMLTTB_URL', 'https://kmlttb.org/professionals/'),
];
