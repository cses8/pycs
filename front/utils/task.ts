/* eslint-disable @typescript-eslint/no-explicit-any */
/**
 * A class for sequential execution of asynchronous tasks. 🚀🧵
 *
 * Queues asynchronous functions and executes them one after another, collecting the results in an array. 🗂️
 * If a task rejects (throws an error), the error is caught, logged to the console with a 🔥 emoji, and `null` is stored in the results array for that task. 🚫
 * Tasks that don't explicitly return a value (return `undefined`) will have `null` stored in the results array. 😶
 *
 * @example Basic Usage: ➕➡️✅
 * ```typescript
 * async function basicExample() {
 *   const results = await task()
 *     .do(async () => {
 *       console.log('Task 1 started 🚦');
 *       await new Promise(resolve => setTimeout(resolve, 500)); // Simulate async operation ⏳
 *       console.log('Task 1 finished ✅');
 *       return 'Result from Task 1';
 *     })
 *     .do(async () => {
 *       console.log('Task 2 started 🚦');
 *       console.log('Task 2 finished ✅');
 *       // No explicit return, will be null in results
 *     })
 *     .end(); // 🏁
 *   console.log('Basic Example Results: 🎉', results); // Output: ['Result from Task 1', null]
 * }
 * basicExample();
 * ```
 *
 * @example Error Handling: ⚠️🔥
 * ```typescript
 * async function errorHandlingExample() {
 *   const results = await task()
 *     .do(async () => 'This will succeed 👍')
 *     .do(async () => {
 *       console.log('About to throw an error 💥');
 *       throw new Error('This will fail ❌');
 *     })
 *     .do(async () => 'This will also succeed 🎉') // This task still executes
 *     .end(); // 🏁
 *   console.log('Error Handling Example Results: 🚨', results); // Output: ['This will succeed 👍', null, 'This will also succeed 🎉']
 * }
 * errorHandlingExample();
 * ```
 *
 * @example Different Result Types: 🔀
 * ```typescript
 * async function differentTypesExample() {
 *   const results = await task()
 *     .do(async () => 123) // 🔢
 *     .do(async () => 'string') // 🔤
 *     .do(async () => true) // ✅ or ❌
 *     .do(async () => ({ key: 'value' })) // 📦
 *     .end(); // 🏁
 *   console.log('Different Types Example Results: 🌈', results); // Output: [123, 'string', true, { key: 'value' }]
 * }
 * differentTypesExample();
 * ```
 *
 * @example No Return Values: 😶➡️✅
 * ```typescript
 * async function noReturnValuesExample() {
 *   const results = await task()
 *     .do(async () => console.log("Task 1 executed ➡️"))
 *     .do(async () => console.log("Task 2 executed ➡️"))
 *     .end(); // 🏁
 *   console.log('No Return Values Example Results: ∅', results); // Output: [null, null]
 * }
 * noReturnValuesExample();
 * ```
 */

export default (): SequentialExecutor<[]> => {
  return new SequentialExecutor<[]>()
}

/**
 * Creates a new SequentialExecutor instance. ✨
 * @returns A new SequentialExecutor instance. 📦
 */
class SequentialExecutor<T extends any[]> {
  private tasks: ((results: [...T] | undefined) => Promise<any>)[] = [] // Change here
  private results: any[] = [] // Changed this to any[]

  constructor() {
    this.tasks = []
    this.results = []
  }

  /**
   * Adds a task to the execution queue. ➕
   * @param task The asynchronous function to execute. ⚙️
   * @returns The SequentialExecutor instance for chaining. 🔗
   * @throws {Error} If the provided task is not a function. 🚫
   */
  do<U>(
    task: (results: [...T]) => Promise<U>
  ): SequentialExecutor<[...T, U | null]> {
    if (typeof task !== 'function') {
      throw new Error('Task must be a function. 🚫')
    }
    this.tasks.push(task as any) // Type assertion here
    return this as unknown as SequentialExecutor<[...T, U | null]>
  }

  /**
   * Executes all queued tasks sequentially. 🏁
   * @returns A promise that resolves to an array of results. 🏆 Each element corresponds to the result of a task, or null if the task didn't return a value or threw an error. 😶
   */
  async end(): Promise<[...T]> {
    // Return type is now [...T]
    for (const task of this.tasks) {
      try {
        const result = await task(this.results as [...T])
        this.results.push(result === undefined ? null : result)
      } catch (error) {
        console.error('Error during task execution: 🔥', error)
        this.results.push(null)
      }
    }
    return this.results as [...T] // Type assertion here
  }
}
