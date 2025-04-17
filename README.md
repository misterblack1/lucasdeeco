## Lucas Deeco Terminal Information 

High resolution photos and ROM dumps: [https://archive.org/details/lucas-deeco-seal-touch](https://archive.org/details/lucas-deeco-seal-touch)

The Lucas Deeco terminal uses an Intel 80186 CPU with a full 16 bit data bus. The system contains a system ROM (64k) along with SRAM for system memory and EEPROMs for storing system settings and user data.

Dumped system ROMs are scrambled on address lines A0-A6. A 18CV8PC-25 PAL sits between the CPU and the ROM/RAM address bus and appears to be there to allow the CPU to read a ROM with scrambled contents. It appears a PAL scrambles the data in 16 byte chunks. (32 bytes when you combine the two ROM HI/LOW files.) There appear to be 8 "keys" which are the different scrambled address line combinations, repeating every 256 bytes. See below:

```
CPU    74LS373             18CV8PC-25 ROM/RAM

                           1 CLK ?
                           11    ?
                           19    ?
DA0                         2 -> ?    --
DA1                         3 -> 18   A0
DA2                         4 -> 17   A1
DA3                         5 -> 16   A2
DA4                         6 -> 15   A3
DA5                         7 -> 14   A4
DA6                         8 -> 13   A5
DA7                         9 -> 12   A6
DA8    Pin 18 -> Pin 15               A7
DA9    Pin 17 -> Pin 16               A8
DA10   Pin 14 -> Pin 15               A9
DA11   Pin 13 -> Pin 12               A10
DA12   Pin 8  -> Pin 9                A11
DA13   Pin 7  -> Pin 6                A12
DA14   Pin 4  -> Pin 5                A13
DA15   Pin 3  -> Pin 2                A14

DA0 - DA15 go to a pair of 74LS245's which are then connected to D0-D15 on the RAM/ROM/EEPROMs. 
```

This means that the "key" used for the first 16 bytes of the ROMs is the same key used every 256 bytes through the entire length of the ROM. (Each chip is 32k in size, so 64k in total as this is a 16 bit system and the ROMs are low and high byte.)

Example from the start of the ROM:

A0,1,2,3,4,5,6 (native mapping as if PAL was not there, data is scrambled)
```
..) ONN .I...*..}.DIIC19M...M........ .4...&.........+`....=...#COGIRP..........PYTAOR1..............U..u............V..........RIL S 86........GHELCO-...............;..t...@u....t....40...u..T ECAT..........(CTRIO...........
```

A3,5,6,0,1,2,4 (first 32 bytes look correct, but beyond that, still scambled.)
```
..}.COPYRIGHT (C) DIGITAL ELECTRONICRPORS COATION 19..1.86-......IM..............................*M............................................T...........t...). .+.U.V.........4`.....;...........u....t40.....&.=.....................@.u.$.....#....u.......
```

A3,6,5,0,1,2,4 (next 32 bytes look correct)
```
..}.RIGHCOPYT (C) DIL ELGITAECTRONICS CORPORATION 1986-...1......IM..............................*M............................................T.......t.......). .+.....U.V.....4`.;................t40u........&.=.................@.u.....$.....#u...........
```

The complete string is `COPYRIGHT (C) DIGITAL ELECTRONICS CORPORATION 1986` but crosses the boundary so requires two keys to decode entirely.

## Descrambling the ROM

Patron [Peter Tirsek](https://github.com/tirsek/lucasdeeco) has created a solution:

>After a little trial and error, it looks like the "key" is pretty  straight forward. I ended up not numbering them the same way you did, 
 but the structure is probably pretty obvious:

```
# Output bit number
#0, 1, 2,3, 4,5, 6, 7
[0, 4, 6,7, 1,2, 3, 5], # <-- Input bit number
[0, 4, 7,6, 1,2, 3, 5],
[0, 4, 6,7, 2,1, 3, 5],
[0, 4, 7,6, 2,1, 3, 5],
[0, 3, 6,7, 1,2, 4, 5],
[0, 3, 7,6, 1,2, 4, 5],
[0, 3, 6,7, 2,1, 4, 5],
[0, 3, 7,6, 2,1, 4, 5],
```

>As you said yourself, the "key" switches every 32 bytes and cycles after 8 keys or 256 bytes total.
>
>At least this arrangement gives me something where all strings appear  readable, and the disassembled code looks plausible. I'll send an e-mail with the descrambled ROM.

Peter [created a tool](https://github.com/misterblack1/lucasdeeco/tree/main/rom_descrambler) to descramble and scramble the ROM image. That means it is now possible to examine the code from the terminal but also make changes to it allosw you to run modified code on the real hardware. (Untested at this point)
