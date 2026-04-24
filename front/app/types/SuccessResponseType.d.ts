declare type SuccessResponseType<T> = {
	status: "success";
  message: string;
  data: T;
  httpCode: 201 | 200
	pagination?: {
		curPage: number,
		from: number,
		to: number,
		perPage: number,
		lastPage: number,
		total: number,
	};

	// old type, deprecated soon
  statusCode?: number;
	descriptions?: T
}