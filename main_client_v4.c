/*

	Client Code
	Arduino Sensor Data --> Raspberry Zero ( Data Filtering ) --> Kaa Server ( MongoDB )

*/

#include <stdio.h>
#include <stdint.h>
#include <wiringPi.h>
#include <wiringSerial.h>
#include <string.h>
#include <stdlib.h>
#include <signal.h>
#include <errno.h>
#include <pthread.h>
#include <unistd.h>
#include <fcntl.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <sys/wait.h>
#include <netdb.h>
#include <netinet/in.h>
#include <assert.h>
#include <arpa/inet.h>
#include <netinet/tcp.h>
#include <mysql.h>

// Data Filtering //
#define DANGEROUS_TEMP 38.0
#define DANGEROUS_BEAT 130
#define DANGEROUS_ECG 100
#define BUFFER 1024

#define DB_HOST "localhost"
#define DB_USER "root"
#define DB_PASS "root"
#define DB_NAME "Hospital"


// Find Serial device on Raspberry with ~ls /dev/tty*
// ARDUINO "/dev/ttyACM0"
// FTDI_PROGRAMMER "/dev/ttyUSB0"
// HARDWARE_UART "/dev/ttyAMA0"
char device[]="/dev/ttyUSB0";
char device2[]="/dev/ttyACM0";

int fd;
unsigned long baud = 9600;
unsigned long Atime=0;
int heartbeat=0, ECG=0, temp=0;
int t_heartbeat, t_ECG, t_temp;
int danger=0; //0:정상 1:심장마비 2:체온변화 3:그 외

int pnum = 1, age = 24; //기본 USER
void setup(void);
void error_handling(char *message);
void data_filtering(void);
void *sensing(void *arg);
void *db_saving(void);

void setup(){
	if ((fd = serialOpen(device, baud)) < 0){
		perror("wiringPi setup");
		exit(1);
	}

	if ( wiringPiSetup() == -1){	
		perror("wiringPi setup2");
		exit(1);
	}
}

void data_filtering(){
	//심박정상범위판별
	if(heartbeat <= 220 | heartbeat >= 0){
		t_heartbeat = heartbeat;
		if(heartbeat < 45)
			danger = 1;
		else danger = 0;
	}
	else heartbeat = t_heartbeat;
	
	//체온정상범위판별
	if(temp >= 34.5 | temp <= 38.5){
		t_temp = temp;
		danger = 0;
	}
	else if(temp > 38.5 | temp < 40) 
		danger = 2;
	else temp = t_temp;
}

void *sensing(void *arg){
	char buf[200], flushBuf[200]={0, };
	char *ptr;
	char bufStr[20];
	int i = 0;

	setup();

	while(1){
	  if(millis()-Atime>=3000){
	    serialPuts (fd, "serial_sensing \n");'
	    serialPutchar (fd, 65);
	    Atime=millis();
	  }

 	 if(serialDataAvail (fd)){
		buf[i++] = serialGetchar(fd);
		if(i>60){
			ptr = strchr(buf, '!');
			strncpy(bufStr,ptr+1,3);
			if(bufStr[2]==',')
				bufStr[2]='\n';
			else bufStr[3]='\n';
			heartbeat = atoi(bufStr);			
			
			ptr = strchr(buf, '@');
			strncpy(bufStr,ptr+2,3);
			bufStr[3]='\n';
			ECG = atoi(bufStr);

			ptr = strchr(buf, '%');
			strncpy(bufStr,ptr+1,5);
			bufStr[5]='\n';
			temp = atoi(bufStr);
			
			printf("heartbeat : %d , ECG : %d, temp : %d\n", heartbeat, ECG, temp);
			data_filtering();
			strcpy(buf, flushBuf);
			i = 0;
			
		}
		
  	  fflush(stdout);
	  }
	}	
}

MYSQL* mysql_connection_setup() {
 
    MYSQL *connection = mysql_init(NULL);
 
    if(!mysql_real_connect(connection, DB_HOST, DB_USER, DB_PASS, DB_NAME, 0, NULL, 0)) {
 
        printf("Connection error : %s\n", mysql_error(connection));
        exit(1);
 
    }
    return connection;
}

MYSQL_RES* mysql_perform_query(MYSQL *connection, char *sql_query) {
 
    if(mysql_query(connection, sql_query)) {
 
        printf("MYSQL query error : %s\n", mysql_error(connection));
        exit(1);
 
    }
    return mysql_use_result(connection);
}

void *db_saving(void){
	MYSQL *conn;
	MYSQL_RES *res;
	MYSQL_RES *res2;
	MYSQL_RES *res3;
	MYSQL_ROW row;
	char query[256];
	time_t now = time(NULL);
	
	
	conn = mysql_connection_setup();
	res = mysql_perform_query(conn, "show tables");
	while((row = mysql_fetch_row(res)) != NULL)
        printf("%s\n", row[0]);

	while(1){
		char* table_name = "patient";
		struct tm tm = *localtime(&now);
		
		sprintf(query, "insert into %s (pnum, RT_time, ECG, HR, temperature) value (%d,%d,%d,%d,%d);", table_name, pnum, tm.tm_hour*3600+tm.tm_min*60+tm.tm_sec, ECG, heartbeat, temp);
		printf("%s\n",query);
		res = mysql_perform_query(conn, query);
		
		if(danger != 0){
			sprintf(query, "update Emergency set HT_attack=%d where pnum='%d'", danger, pnum);
			printf("%s\n",query);
			res2 = mysql_perform_query(conn, query);
		}
		sleep(3);
		}
}

int main(int argc, char* argv[]){
	char message[BUFFER];
	char *serv_msg = "";
	int sock, str_len, i=0;
	int opt = 1; 
	struct sockaddr_in serv_addr;

	setup();
	
	if(argc !=3)
	{
		printf("Usage : %s <ip> <port>\n", argv[0]);
		exit(1);
	}

	printf("당신의 pnum, age 을 입력하세요\n");
	scanf("%d", &pnum);
	scanf("%d", &age);

	
	pthread_t sensor_thread;
	if (pthread_create(&sensor_thread, NULL, &sensing, NULL))
		printf("thread create failed!!\n");
		
	pthread_t db_thread;
	if (pthread_create(&db_thread, NULL, &db_saving, NULL))
		printf("thread2 create failed!!\n");
	
	sock = socket(PF_INET, SOCK_STREAM, 0);
	setsockopt(sock, IPPROTO_TCP, TCP_NODELAY, (void *)&opt, sizeof(opt));
	if(sock == -1)
		error_handling("socket() error");
	
	memset(&serv_addr, 0, sizeof(serv_addr));
	serv_addr.sin_family=AF_INET;
	serv_addr.sin_addr.s_addr = inet_addr(argv[1]);
	serv_addr.sin_port = htons(atoi(argv[2]));
	
	if(connect(sock, (struct sockaddr*)&serv_addr, sizeof(serv_addr))==-1)
		error_handling("connect() error");
	
	printf("connect\n");
	
	while(1){
		
		sleep(3);
		printf("message writing ..");
		printf("pnum : %03d, age : %03d, heartbeat : %03d , ECG : %03d, temp : %02d\n", pnum, age, heartbeat, ECG, temp);
		sprintf(message, "!%03d^%03d@%03d#%03d$%02d", pnum, age, heartbeat, ECG, temp);
		write(sock, message, strlen(message)+1);	
	}
	sleep(100);
	close(sock);
	return 0;
}

void error_handling(char *message)
{
	fputs(message, stderr);
	fputc('\n', stderr);
	exit(1);
}
