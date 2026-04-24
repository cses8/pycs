import cloneDeep from 'lodash.clonedeep'
/**
 * ✨ Chainable class to update properties of an object or primitive. ✨
 *
 * 🔗 **Chainable:** Allows you to chain multiple `set`, `assign`, `pushTo`, `setTo`, or `assignTo` calls for convenient updates.
 * 🎯 **Direct Value:** Accepts the new value for direct assignment or an updater function.
 * 📖 **Clear Naming:** Uses descriptive methods for clarity (e.g., `set`, `assign`, `pushTo`).
 * 🧅 **Nested Properties:** Supports updating nested properties within an object.
 * 💡 **Flexible Types:** Handles numbers, strings, booleans, and objects with primitive values.
 * 🚀 **Immutable Updates:** Creates a new object or primitive, leaving the original unchanged.
 *
 * @example
 * // With plain values:
 * const mutate1 = new Mutate(0).set(1);
 * console.log(mutate1.get()); // Output: 1
 *
 * // With updater function:
 * const mutate2 = new Mutate(1).set(count => count + 1);
 * console.log(mutate2.get()); // Output: 2
 *
 * // With objects:
 * const myObj = { name: "Alice", age: 30 };
 * const mutate3 = new Mutate(myObj)
 *   .assign("name", "Bob")
 *   .assign("age", age => age + 1);
 * console.log(mutate3.get()); // Output: { name: "Bob", age: 31 }
 *
 * // With arrays:
 * const myArray = [1, 2, 3];
 * new Mutate(4).pushTo(myArray);
 * console.log(myArray); // Output: [1, 2, 3, 4]
 *
 * // Assigning to a variable:
 * let myVariable = 10;
 * new Mutate(20).setTo(myVariable);
 * console.log(myVariable); // Output: 20
 *
 * // Adding properties to an object:
 * const myObj2 = { name: "Alice" };
 * new Mutate({ age: 30 }).assignTo(myObj2);
 * console.log(myObj2); // Output: { name: "Alice", age: 30 }
 */
class Mutate {
  /**
   * Creates a new Mutate instance.
   * @param {Primitive | RecordOfPrimitives} obj - The initial object or primitive value.
   */
  constructor(obj) {
    this.obj = obj
  }

  /**
   * Sets the value directly if the object is a primitive.
   *
   * @param {Primitive} newValue - The new value to set.
   * @returns {Mutate} The Mutate instance for chaining.
   * @throws {Error} If called on an object. Use `assign` for objects.
   *
   * @example
   * const mutate = new Mutate(10).set(20);
   * console.log(mutate.get()); // Output: 20
   */
  set(newValue) {
    if (typeof this.obj === 'object') {
      throw new Error(
        "Cannot directly set value on an object. Use 'assign' instead."
      )
    }
    this.obj = newValue
    return this
  }

  /**
   * Assigns a new value to a property of the object.
   *
   * @param {string} key - The property key to update.
   * @param {Primitive | PrimitiveFunction} newValue - The new value or an updater function.
   * @returns {Mutate} The Mutate instance for chaining.
   * @throws {Error} If called on a primitive value. Use `set` for primitives.
   *
   * @example
   * const myObj = { name: "Alice", age: 30 };
   * const mutate = new Mutate(myObj).assign("name", "Bob");
   * console.log(mutate.get()); // Output: { name: "Bob", age: 30 }
   */
  assign(key, newValue) {
    if (typeof this.obj !== 'object') {
      throw new Error(
        "Cannot assign property on a primitive value. Use 'set' instead."
      )
    }
    if (typeof newValue === 'function') {
      this.obj[key] = newValue(this.obj[key])
    } else {
      this.obj[key] = newValue
    }
    return this
  }

  assignRaw(key, newValue) {
    if (typeof this.obj !== 'object') {
      throw new Error(
        "Cannot assign property on a primitive value. Use 'set' instead."
      )
    }
    if (typeof newValue === 'function') {
      this.obj[key] = newValue(this.obj)
    } else {
      this.obj[key] = newValue
    }
    return this
  }

  /**
   * Pushes the current value to an array.
   *
   * @param {Array} array - The array to push the value to.
   * @returns {Mutate} The Mutate instance for chaining.
   * @throws {TypeError} If the provided argument is not an array.
   *
   * @example
   * const myArray = [1, 2, 3];
   * new Mutate(4).pushTo(myArray);
   * console.log(myArray); // Output: [1, 2, 3, 4]
   */
  pushTo(array) {
    if (!Array.isArray(array)) {
      throw new TypeError(
        "The 'pushTo' method expects an array as an argument."
      )
    }
    array.push(cloneDeep(this.obj))
    return this
  }

  /**
   * Sets the value of a variable.
   *
   * @param {Primitive} variable - The variable to assign the value to.
   * @returns {Mutate} The Mutate instance for chaining.
   * @throws {TypeError} If the target is not a primitive value.
   *
   * @example
   * let myVariable = 10;
   * new Mutate(20).setTo(myVariable);
   * console.log(myVariable); // Output: 20
   */
  setTo(variable) {
    if (
      typeof variable !== 'string' &&
      typeof variable !== 'number' &&
      typeof variable !== 'boolean'
    ) {
      throw new TypeError(
        "The 'setTo' method expects a primitive variable (string, number, or boolean) as the target."
      )
    }
    variable = cloneDeep(this.obj)
    return this
  }

  /**
   * Adds properties from the current value (an object) to another object.
   *
   * @param {Record<string, any>} obj - The object to add properties to.
   * @returns {Mutate} The Mutate instance for chaining.
   * @throws {TypeError} If the provided argument is not an object.
   * @throws {Error} If the current value is not an object.
   *
   * @example
   * const myObj = { name: "Alice", age: 30 };
   * new Mutate({ city: "New York" }).assignTo(myObj);
   * console.log(myObj); // Output: { name: "Alice", age: 30, city: "New York" }
   */
  assignTo(obj) {
    if (typeof obj !== 'object' || obj === null) {
      throw new TypeError(
        "The 'assignTo' method expects an object as an argument."
      )
    }
    if (typeof this.obj !== 'object') {
      throw new Error("The 'assignTo' method can only be used with objects.")
    }
    Object.assign(obj, cloneDeep(this.obj))
    return this
  }

  /**
   * Gets the current value of the object or primitive.
   *
   * @returns {Primitive | RecordOfPrimitives} The current value.
   */
  get() {
    return this.obj
  }
}

export default obj => new Mutate(obj)
