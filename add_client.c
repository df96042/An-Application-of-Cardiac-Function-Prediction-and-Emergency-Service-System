/*

	Client Code
	Arduino Sensor Data --> Raspberry Zero ( Data Filtering ) --> Kaa Server ( MongoDB )

*/

#include <stdio.h>
#include <stdint.h>
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
#define DANGEROUS_BEAT 100
#define DANGEROUS_ECG 100
#define BUFFER 1024

#define DB_HOST "localhost"
#define DB_USER "root"
#define DB_PASS "root"
#define DB_NAME "Hospital"

int pnum; //user 구분
float MPHR;

void error_handling(char *message);
void cal_MPHR(int age);


void cal_MPHR(int age){
	MPHR = (220-age)*85/100;
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

int main(){
	char message[BUFFER];
	char name[20];
	int age;
	int HR_avg;
	
	while(1){
		
		printf("pnum | name | age 를 입력하세요\n");
		scanf("%d", &pnum);
		scanf("%s", name);
		scanf("%d", &age);
	
		cal_MPHR(age);
		HR_avg = (age*3);
	
		//db saving
		MYSQL *conn;
		MYSQL_RES *res;
		MYSQL_ROW row;
		char query[256];
	
		conn = mysql_connection_setup();
		char* table_name = "patient_data";
		
		sprintf(query, "insert into %s (pnum, name, age, HR_avg, MPHR) value (%d,\"%s\",%d,%d,%f);", table_name, pnum, name, age, HR_avg, MPHR);
		printf("%s\n",query);
		res = mysql_perform_query(conn, query);
		
		printf("입력완료\n");
		sleep(5);
	}
	

	sleep(100);

	return 0;
}

void error_handling(char *message)
{
	fputs(message, stderr);
	fputc('\n', stderr);
	exit(1);
}
