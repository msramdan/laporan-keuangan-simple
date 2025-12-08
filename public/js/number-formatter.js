/**
 * Number Formatting Helper
 * Real-time auto-formats number inputs with thousand separators (dots)
 * Uses event delegation for dynamic elements
 */
(function () {
    "use strict";

    const SELECTORS = [
        'input[data-format="number"]',
        'input[data-format="currency"]',
        "input.format-number",
        "input.format-currency",
    ].join(",");

    /**
     * Format a number with Indonesian thousand separators
     * @param {number|string} value - The number to format
     * @returns {string} - Formatted number string
     */
    function formatNumber(value) {
        if (value === "" || value === null || value === undefined) return "";

        // Remove existing separators to get raw value
        let rawValue = value.toString().replace(/\./g, "").replace(/,/g, ".");
        let num = parseFloat(rawValue);

        if (isNaN(num)) return "";
        if (num === 0) return "0";

        // Check if it's a whole number
        let formattedValue;
        if (Number.isInteger(num)) {
            formattedValue = num.toString();
        } else {
            // Keep up to 2 decimal places, remove trailing zeros
            formattedValue = num.toFixed(2).replace(/\.?0+$/, "");
        }

        // Add thousand separators (dots)
        let parts = formattedValue.split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");

        // Use comma for decimal separator (Indonesian format)
        return parts.join(",");
    }

    /**
     * Parse a formatted number string back to a raw number
     * @param {string} value - The formatted string
     * @returns {number} - Raw number value
     */
    function parseFormattedNumber(value) {
        if (!value || value === "") return 0;

        // Remove thousand separators (dots) and convert comma to dot for decimals
        let cleaned = value
            .toString()
            .replace(/\./g, "") // Remove thousand separators
            .replace(/,/g, "."); // Convert decimal comma to dot

        return parseFloat(cleaned) || 0;
    }

    /**
     * Format value in real-time while preserving cursor position
     * @param {HTMLInputElement} input - The input element
     */
    function formatInputRealtime(input) {
        const cursorPos = input.selectionStart;
        const oldValue = input.value;
        const oldLength = oldValue.length;

        // Count dots before cursor in old value
        const dotsBefore = (oldValue.substring(0, cursorPos).match(/\./g) || [])
            .length;

        // Get raw value (only digits)
        const rawValue = oldValue.replace(/[^\d]/g, "");

        // Format the number
        if (rawValue === "") {
            input.value = "";
            return;
        }

        const num = parseInt(rawValue, 10);
        const formatted = num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

        input.value = formatted;

        // Calculate new cursor position
        const newLength = formatted.length;
        const dotsAfter = (
            formatted
                .substring(0, cursorPos + (newLength - oldLength))
                .match(/\./g) || []
        ).length;
        let newCursorPos = cursorPos + (dotsAfter - dotsBefore);

        // Ensure cursor is within bounds
        newCursorPos = Math.max(0, Math.min(newCursorPos, newLength));

        // Set cursor position
        try {
            input.setSelectionRange(newCursorPos, newCursorPos);
        } catch (e) {
            // Some browsers may not support this for certain input types
        }
    }

    /**
     * Check if element matches our selectors
     */
    function isNumberInput(element) {
        return element && element.matches && element.matches(SELECTORS);
    }

    /**
     * Initialize formatting on page load
     */
    function initializeExisting() {
        document.querySelectorAll(SELECTORS).forEach(function (input) {
            if (input.value) {
                const rawValue = parseFormattedNumber(input.value);
                if (rawValue > 0) {
                    input.value = formatNumber(rawValue);
                }
            }
        });
    }

    // Use event delegation on document for all events
    // This ensures dynamically added elements work automatically

    // Real-time formatting on input
    document.addEventListener(
        "input",
        function (e) {
            if (isNumberInput(e.target)) {
                formatInputRealtime(e.target);
            }
        },
        true
    );

    // Prevent non-numeric keys
    document.addEventListener(
        "keydown",
        function (e) {
            if (!isNumberInput(e.target)) return;

            // Allow: backspace, delete, tab, escape, enter, arrows
            if ([8, 9, 13, 27, 46, 37, 38, 39, 40].includes(e.keyCode)) {
                return;
            }
            // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            if (
                (e.ctrlKey || e.metaKey) &&
                [65, 67, 86, 88].includes(e.keyCode)
            ) {
                return;
            }
            // Allow: home, end
            if ([35, 36].includes(e.keyCode)) {
                return;
            }
            // Block non-numeric characters
            if (
                (e.shiftKey && e.keyCode >= 48 && e.keyCode <= 57) ||
                ((!e.shiftKey && e.keyCode >= 48 && e.keyCode <= 57) ===
                    false &&
                    (e.keyCode < 96 || e.keyCode > 105))
            ) {
                // Allow numpad and number row (without shift)
                if (
                    !(
                        (e.keyCode >= 48 && e.keyCode <= 57) ||
                        (e.keyCode >= 96 && e.keyCode <= 105)
                    )
                ) {
                    e.preventDefault();
                }
            }
        },
        true
    );

    // Select all on focus
    document.addEventListener(
        "focus",
        function (e) {
            if (isNumberInput(e.target)) {
                setTimeout(function () {
                    try {
                        e.target.select();
                    } catch (err) {}
                }, 10);
            }
        },
        true
    );

    // Handle form submission - replace formatted values with raw values
    document.addEventListener(
        "submit",
        function (e) {
            if (e.target.tagName === "FORM") {
                e.target.querySelectorAll(SELECTORS).forEach(function (input) {
                    input.value = parseFormattedNumber(input.value);
                });
            }
        },
        true
    );

    // Expose to global scope
    window.NumberFormatter = {
        format: formatNumber,
        parse: parseFormattedNumber,
        init: initializeExisting,
        formatInput: formatInputRealtime,
    };

    // Auto-initialize on DOM ready
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initializeExisting);
    } else {
        initializeExisting();
    }
})();
