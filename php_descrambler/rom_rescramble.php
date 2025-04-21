#!/usr/bin/php
<?php

    /*
        Lucas Deeco SealTouch ST3220
        ROM srambler and decomposer

        (C) 2025, Daniel Rozsnyo < daniel@rozsnyo.com >
    */

    require_once 'func.rom_data.inc.php';
    require_once 'func.rom_addr.inc.php';

    // load NEW image for processing
    $cpu = rom_data_load('new_CPU_64K.bin');

    // apply scrambling
    $rom = rom_data_encode($cpu);

    // debug save
    rom_data_save('new_ROM_64K.bin',$rom);

    // split to LO/HI memories
    $lo = rom_data_slice($rom,0,2);
    $hi = rom_data_slice($rom,1,2);

    // final save
    rom_data_save('enc_U28_27256_LO_32K.bin',$lo);
    rom_data_save('enc_U10_27256_HI_32K.bin',$hi);

?>
