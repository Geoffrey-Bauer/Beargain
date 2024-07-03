document.addEventListener('DOMContentLoaded', function () {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const totalPriceElement = document.getElementById('totalPrice');
    const nightPrice = parseFloat(document.getElementById('nightPrice').innerText); // Convertir le prix par nuit en nombre décimal

    startDateInput.addEventListener('change', updateTotalPrice);
    endDateInput.addEventListener('change', updateTotalPrice);

    function updateTotalPrice() {
        const startDateValue = new Date(startDateInput.value);
        const endDateValue = new Date(endDateInput.value);

        // Vérifier si les dates sont valides et si la date de départ est antérieure ou égale à la date de fin
        if (!isNaN(startDateValue.getTime()) && !isNaN(endDateValue.getTime()) && startDateValue <= endDateValue) {
            const diffTime = Math.abs(endDateValue - startDateValue);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); // Nombre de jours entre les dates

            const totalPriceValue = diffDays * nightPrice;
            totalPriceElement.textContent = totalPriceValue.toFixed(2); // Mettre à jour le prix total avec 2 décimales
        } else {
            // Si la date de fin est antérieure à la date de départ, afficher un prix total de 0
            totalPriceElement.textContent = '0';
            // Réinitialiser la valeur de endDateInput à celle de startDateInput pour éviter des dates invalides
            endDateInput.value = startDateInput.value;
        }
    }
});
