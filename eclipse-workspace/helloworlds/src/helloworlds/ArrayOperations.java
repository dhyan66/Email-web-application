package helloworlds;

import java.util.Arrays;

public class ArrayOperations {
    public static void main(String[] args) {
        int[] numbers = new int[10];

        // Populate the array with numbers 1 to 10
        for (int index = 0; index < numbers.length; index++) {
            numbers[index] = index + 1;
        }

        // Display the array
        System.out.println("Original Array:");
        System.out.println(Arrays.toString(numbers));

        // Calculate and display the sum of the array
        int sum = 0;
        for (int index = 0; index < numbers.length; index++) {
            sum += numbers[index];
        }
        System.out.println("Sum of the Array: " + sum);

        // Find and display the maximum and minimum values
        int max = findMax(numbers);
        int min = findMin(numbers);
        System.out.println("Maximum Value: " + max);
        System.out.println("Minimum Value: " + min);

        // Reverse and display the array
        System.out.println("Reversed Array:");
        for (int i = numbers.length - 1; i >= 0; i--) {
            System.out.print(numbers[i] + " ");
        }
        System.out.println();
    }

    // Method to find the maximum value in the array
    private static int findMax(int[] array) {
        int max = array[0];
        for (int num : array) {
            if (num > max) {
                max = num;
            }
        }
        return max;
    }

    // Method to find the minimum value in the array
    private static int findMin(int[] array) {
        int min = array[0];
        for (int num : array) {
            if (num < min) {
                min = num;
            }
        }
        return min;
    }
}