document.addEventListener('DOMContentLoaded', () => {
    const display = document.getElementById('calculator-display');
    const buttons = document.querySelectorAll('.grid button');

    let currentInput = '';
    let expression = '';

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            const value = button.getAttribute('data-value');

            if (value === 'C') {
                currentInput = '';
                expression = '';
                display.value = '';
            } else if (value === '←') {
                currentInput = currentInput.slice(0, -1);
                display.value = currentInput;
            } else if (value === '=') {
                try {
                    expression = currentInput;
                    const result = eval(expression);
                    display.value = result;
                    currentInput = result.toString();
                } catch (error) {
                    display.value = 'Error';
                    currentInput = '';
                    expression = '';
                }
            } else if (value === '.') {
                // Prevent multiple decimal points in a single number
                const lastNumber = currentInput.split(/[\+\-\*\/]/).pop(); // Get the last number in the expression
                if (!lastNumber.includes('.')) { // Check if the last number already has a decimal point
                    currentInput += value;
                    display.value = currentInput;
                }
            } else {
                currentInput += value;
                display.value = currentInput;
            }
        });
    });
});