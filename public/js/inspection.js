import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', function() {

    // --- Fecha y hora por defecto ---
    const today = new Date();
    const dateStr = today.toISOString().split('T')[0];
    const timeStr = today.toTimeString().substring(0, 5);
    document.getElementById('date_today').value = dateStr;
    document.getElementById('hour_inspection').value = timeStr;
    document.getElementById('date_today2').value = dateStr;
    document.getElementById('hour_inspection2').value = timeStr;

    // --- Select/Deselect All ---
    const selectAllBtn = document.getElementById("check_all");
    const checkboxes = document.querySelectorAll(".checklist");

    selectAllBtn.addEventListener("change", function() {
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // --- Generar PDF ---
    const downloadBtn = document.getElementById("downloadPDF");
    if(downloadBtn) {
        downloadBtn.addEventListener("click", () => {
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF();
            const date = document.getElementById("date_today").value || "2025-09-02";
            const driver = document.getElementById("signature").value || "John Doe";
            const truckNumber = document.getElementById("truck_number").value || "12345";
            const trailerNumber = document.getElementById("trailer1").value || "67890";
            const inspectionType = document.getElementById("pretrip").checked ? "Pre-trip" : 
                                   document.getElementById("posttrip").checked ? "Post-trip" : "Not specified";
            const condition = document.getElementById("nodefect").checked ? "No defects detected" : 
                            document.getElementById("defects").checked ? "Defects detected" : "Not specified";
            const remarks = document.getElementById("remarks").value || "No remarks";

            pdf.setFontSize(16);
            pdf.text("Trip Inspection Report", 105, 15, { align: "center" });
            pdf.setFontSize(10);
            pdf.text(`Date: ${date}`, 15, 30);
            pdf.text(`Driver: ${driver}`, 105, 30);
            pdf.text(`Truck #: ${truckNumber}`, 15, 40);
            pdf.text(`Trailer #: ${trailerNumber}`, 105, 40);
            pdf.text(`Inspection Type: ${inspectionType}`, 15, 50);
            pdf.text(`Condition: ${condition}`, 105, 50);

            pdf.setFontSize(12);
            pdf.text("Inspection Checklist", 15, 70);
            pdf.setFontSize(10);

            let y = 80;
            checkboxes.forEach((checkbox) => {
                if(checkbox.checked){
                    const label = checkbox.nextElementSibling.textContent;
                    if(y > 250) { pdf.addPage(); y = 20; }
                    pdf.rect(15, y - 4, 5, 5);
                    pdf.text("✓", 16.5, y);
                    pdf.text(label, 25, y);
                    y += 7;
                }
            });

            y += 10;
            pdf.setFontSize(12);
            pdf.text("Remarks:", 15, y);
            pdf.setFontSize(10);
            const splitRemarks = pdf.splitTextToSize(remarks, 180);
            pdf.text(splitRemarks, 15, y + 7);

            y += splitRemarks.length * 5 + 15;
            pdf.text("Driver Signature:", 15, y);
            pdf.line(50, y, 120, y);
            pdf.text(driver, 50, y + 5);
            pdf.text("Date:", 15, y + 15);
            pdf.text(date, 50, y + 15);
            pdf.text("Time:", 15, y + 25);
            pdf.text(document.getElementById("hour_inspection").value || "N/A", 50, y + 25);

            pdf.save("Trip_Inspection_Report.pdf");
        });
    }

    // --- Confirmación con SweetAlert + envío AJAX ---
    const form = document.getElementById('myForm');
    form.addEventListener('submit', function(e){
        e.preventDefault(); // detener envío normal

        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to save this inspection report?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, save it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if(result.isConfirmed){
                // Preparar datos
                const formData = new FormData(form);

                // Enviar con fetch
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success){
                        Swal.fire({
                            title: 'Success!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                        form.reset(); // opcional: limpiar form
                    } else {
                        Swal.fire('Error', 'Could not save inspection report.', 'error');
                    }
                })
                .catch(() => Swal.fire('Error', 'Could not save inspection report.', 'error'));
            }
        });
    });

});
