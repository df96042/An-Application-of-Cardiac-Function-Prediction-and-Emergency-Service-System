#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include <arpa/inet.h>
#include <sys/socket.h>

#include <stdio.h>
#include <stdlib.h>
#include <stdint.h>
#include <time.h>
#include <kaa.h>
#include <platform/kaa_client.h>
#include <kaa_error.h>
#include <kaa_configuration_manager.h>
#include <kaa_logging.h>
#include <gen/kaa_logging_gen.h>
#include <platform/kaa_client.h>
#include <utilities/kaa_log.h>
#include <platform-impl/common/ext_log_upload_strategies.h>

void error_handling(char *message);

void error_handling(char *message)
{
	fputs(message, stderr);
	fputc('\n', stderr);
	exit(1);
}

int main(int argc, char *argv[])
{
	int heartbeat, ECG; //ECG : 심전도, EMG : 근전도
	double temp;
	
	int serv_sock;
	int clnt_sock;
	
	struct sockaddr_in serv_addr;
	struct sockaddr_in clnt_addr;
	socklen_t clnt_addr_size;


	char message[20];
	char flushmessage[10] = { 0, };
	char *ptr;
	char bufStr[100];

	int str_len;
	
	if(argc !=2)
	{
		printf("Usage : %s <port>\n", argv[0]);
		exit(1);
	}
	
	serv_sock = socket(PF_INET, SOCK_STREAM, 0);
	if(serv_sock == -1)
		error_handling("socket() error");
	
	memset(&serv_addr, 0, sizeof(serv_addr));
	serv_addr.sin_family=AF_INET;
	serv_addr.sin_addr.s_addr = htonl(INADDR_ANY);
	serv_addr.sin_port = htons(atoi(argv[1]));
	
	if(bind(serv_sock, (struct sockaddr*)&serv_addr, sizeof(serv_addr))==-1)
		error_handling("bind() error");
	
	if(listen(serv_sock, 5)==-1)
		error_handling("listen() error");
	
	clnt_addr_size = sizeof(clnt_addr);
	clnt_sock = accept(serv_sock, (struct sockaddr*)&clnt_addr, &clnt_addr_size);
	if(clnt_sock ==-1)
		error_handling("accept() error");

	printf("Hello!\n\n");

 	for (;;)
	{
		str_len = recv(clnt_sock, message, sizeof(message), 0);
		printf("%s\n", message);
		if (str_len == -1)
			error_handling("read() error!");

		ptr = strchr(message, '@');
		strncpy(bufStr, ptr + 1, 3);
		//if (bufStr[2] == ',')
		//	bufStr[2] = '\n';
		bufStr[3] = '\n';
		heartbeat = atoi(bufStr);

		ptr = strchr(message, '#');
		strncpy(bufStr, ptr + 1, 3);
		bufStr[3] = '\n';
		ECG = atoi(bufStr);

		ptr = strchr(message, '$');
		strncpy(bufStr, ptr + 1, 3);
		bufStr[4] = '\n';
		temp = atof(bufStr);

		printf("temp : %lf, ECG : %d, heartbeat : %d\n", temp, ECG, heartbeat);
		strcpy(bufStr, flushmessage);

	}

	printf("\n\n");
	
	close(clnt_sock);
	close(serv_sock);
	return 0;
}
