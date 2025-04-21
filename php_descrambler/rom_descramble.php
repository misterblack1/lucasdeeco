#!/usr/bin/php
<?php

    /*
        Lucas Deeco SealTouch ST3220
        ROM composer and descrambler

        (C) 2025, Daniel Rozsnyo < daniel@rozsnyo.com >
    */

    require_once 'func.rom_data.inc.php';
    require_once 'func.rom_addr.inc.php';

    // load original ROM chip dumps
    $lo = rom_data_load('../U28 27256 LO Rev K Lucas 1990.bin');
    $hi = rom_data_load('../U10 27256 HI Rev K Lucas 1990.bin');

    // assemble single ROM image in "rom address space"
    $rom = rom_data_merge( $hi, $lo );

    // debug save
    rom_data_save('dec_ROM_64K.bin',$rom);

    // read from the point of view of the CPU
    $cpu = rom_data_decode($rom);

    // final save
    rom_data_save('dec_CPU_64K.bin',$cpu);

?>
