declare type ErrorResponseType = {
	status: "error";
  message: string;
  data: T;
  httpCode: 500 | 401 | 400; 
}