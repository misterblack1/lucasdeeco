#include <stdint.h>
#include <stdio.h>

uint8_t address_permutation_table[8][8] = {
	{0,4,6,7,1,2,3,5},
	{0,4,7,6,1,2,3,5},
	{0,4,6,7,2,1,3,5},
	{0,4,7,6,2,1,3,5},
	{0,3,6,7,1,2,4,5},
	{0,3,7,6,1,2,4,5},
	{0,3,6,7,2,1,4,5},
	{0,3,7,6,2,1,4,5},
};

int main()
{
	int i;
	uint8_t forward_map[256], reverse_map[256];

	for (i=0; i<256; i++) {
		uint8_t mapped_address = 0;
		for (int bit=0; bit<8; bit++) {
			mapped_address |= ((i >> bit) & 1) << address_permutation_table[i >> 5][bit];
		}
		forward_map[mapped_address] = i;
		reverse_map[i] = mapped_address;
	}

	printf(
		"#ifndef TABLES_H\n"
		"#define TABLES_H\n"
		"\n"
		"static uint8_t forward_map[256] = {");
	for (i=0; i<256; i++) {
		if (i % 16 == 0) {
			printf("\n\t");
		}
		printf("0x%02hhx,", forward_map[i]);
	}
	fputs(
		"\n"
		"};\n"
		"\n"
		"static uint8_t reverse_map[256] = {"
		, stdout);
	for (i=0; i<256; i++) {
		if (i % 16 == 0) {
			printf("\n\t");
		}
		printf("0x%02hhx,", reverse_map[i]);
	}
	puts(
		"\n"
		"};\n"
		"\n"
		"#endif");
}
