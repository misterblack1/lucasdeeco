#include <errno.h>
#include <fcntl.h>
#include <getopt.h>
#include <stdint.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>

#include "lookup_tables.h"

#define SIZE 256

struct options {
	uint8_t *lookup_table;
	const char *input_filename;
	const char *output_filename;
};

static void parse_commandline(int argc, char **argv, struct options *options)
{
	memset(options, 0, sizeof(*options));

	options->lookup_table = forward_map;

	int help = 0;
	int c;

	while ((c = getopt(argc, argv, "dh")) >= 0) {
		switch (c) {
		case 'd':
			options->lookup_table = reverse_map;
			break;

		case '?':
		case 'h':
			help = 1;
			break;
		}
	}

	if (help || argc - optind > 2) {
		fputs(
			"Usage: lucas_deeco_rom [-d] [input_file [output_file]]\n"
			"\n"
			"This tool scrambles or descrambles the contents of a Lucas Deeco ROM.\n"
			"\n"
			"OPTIONS\n"
			"    -d  Descramble a ROM file"
			, stderr);
		exit(1);
	}

	options->input_filename  = argc - optind > 0 ? argv[optind + 0] : "-";
	options->output_filename = argc - optind > 1 ? argv[optind + 1] : "-";
}

static void open_files(const struct options *options)
{
	if (strcmp(options->input_filename, "-") != 0) {
		int fd = open(options->input_filename, O_RDONLY);
		if (fd < 0) {
			fprintf(stderr, "open(%s): %s", options->input_filename, strerror(errno));
			exit(2);
		}
		if (dup2(fd, STDIN_FILENO) < 0) {
			fprintf(stderr, "dup2(input): %s", strerror(errno));
			exit(2);
		}
		close(fd);
	}

	if (strcmp(options->output_filename, "-") != 0) {
		int fd = open(options->output_filename, O_WRONLY | O_CREAT | O_TRUNC, 0666);
		if (fd < 0) {
			fprintf(stderr, "open(%s): %s", options->output_filename, strerror(errno));
			exit(2);
		}
		if (dup2(fd, STDOUT_FILENO) < 0) {
			fprintf(stderr, "dup2(output): %s", strerror(errno));
			exit(2);
		}
		close(fd);
	}
}

static void convert(const struct options *options)
{
	uint8_t input_buffer[SIZE], output_buffer[SIZE];

	for (;;) {
		ssize_t n = read(STDIN_FILENO, input_buffer, SIZE);
		if (n < 0) {
			fprintf(stderr, "read(): %s", strerror(errno));
			exit(2);
		}
		else if (n == 0) {
			break;
		}
		else if (n < SIZE) {
			fputs("Error: Input was not a multiple of 256 bytes!\n", stderr);
			exit(2);
		}

		for (int i=0; i<SIZE; i++) {
			output_buffer[i] = input_buffer[options->lookup_table[i]];
		}
		n = write(STDOUT_FILENO, output_buffer, SIZE);
		if (n < 0) {
			fprintf(stderr, "write(): %s\n", strerror(errno));
		}
		if (n < SIZE) {
			fprintf(stderr, "write(): Short write\n");
		}
	}
}

int main(int argc, char **argv)
{
	struct options options;
	parse_commandline(argc, argv, &options);
	open_files(&options);
	convert(&options);
}
