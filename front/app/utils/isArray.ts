export default (arr: any): Boolean =>  typeof arr == 'object' 
&& arr !== null 
&& Array.isArray(arr)