Lucas Deeco Terminal ROM - Descrambling Instructions
=====================================================

Overview:
---------
This document explains how to fully descramble the 64KB system ROM from a Lucas Deeco terminal.
The terminal uses an 80186 CPU with two 27256 (32KB) EPROMs: a low byte and a high byte.
An 18CV8PC PAL device performs address scrambling on both ROM halves.

ROM Layout:
-----------
- Size: 64KB total (two 27256 x 8 EPROMs).
- Format: Interleaved 16-bit data (even addresses from LO ROM, odd addresses from HI ROM).

Descrambling Process:
---------------------
1. Merge the two 27256 EPROMs into a single interleaved binary file (64KB).
   - Even bytes: LO ROM (address 0x0000, 0x0002, 0x0004, ...)
   - Odd bytes:  HI ROM (address 0x0001, 0x0003, 0x0005, ...)

2. Descramble the lower 16KB (0x0000–0x3FFF):
   - Divide each 256-byte block into 8 × 16-byte segments.
   - Reorder the segments using the fixed permutation:
       [0, 2, 3, 4, 5, 1, 6, 7]
   - This recovers clear ASCII configuration strings.

3. Descramble the upper 48KB (0x4000–0xFFFF):
   - Divide each 256-byte block into 8 × 32-byte segments.
   - For each segment:
     - Try all 120 permutations of A0–A4 (5-bit address lines).
     - Apply each permutation to the segment's address mapping.
     - Choose the permutation that produces the most readable ASCII.
   - Concatenate the best permutations to reconstruct each block.

4. Combine all descrambled blocks into a 64KB ROM.

Why Strings May Not Show Up in a Hex Editor:
--------------------------------------------
Although the ROM has been descrambled, text strings are often split across high/low bytes.
This means ASCII characters are encoded as 16-bit values, with:
   - One byte from the LO ROM (even address)
   - One byte from the HI ROM (odd address)

When viewed in a hex editor or text tool that assumes single-byte ASCII, these interleaved
characters appear corrupted or hidden. To extract readable text:

How to Extract Readable Text:
-----------------------------
1. Use a tool like `strings16` or a custom script to:
   - Merge high/low bytes into 16-bit words
   - Extract ASCII from either:
       a. Every other byte (e.g., LO only)
       b. Most readable byte of each 16-bit word

2. You can also write a script to:
   - Treat even bytes as ASCII characters
   - Skip odd bytes
   - Filter only printable characters

Example (Python):
-----------------
```python
with open("Lucas_Deeco_ROM_descrambled.bin", "rb") as f:
    data = f.read()
ascii_from_lo = ''.join(chr(b) if 32 <= b <= 126 else '.' for b in data[::2])
print(ascii_from_lo)
```

This will display most of the human-readable configuration data from the ROM.

File Descrambled By:
--------------------
This file was descrambled using a brute-force recovery approach by testing ASCII readability
of each block. Segment permutations were manually mapped where necessary.
