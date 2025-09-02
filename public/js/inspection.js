

// Set default date and time values
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const dateStr = today.toISOString().split('T')[0];
            const timeStr = today.toTimeString().substring(0, 5);
            
            document.getElementById('date_today').value = dateStr;
            document.getElementById('hour_inspection').value = timeStr;
            document.getElementById('date_today2').value = dateStr;
            document.getElementById('hour_inspection2').value = timeStr;
        });

        // Select/Deselect All functionality
        document.getElementById("check_all").addEventListener("change", function() {
            let checkboxes = document.querySelectorAll(".checklist");
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        // PDF Generation
        document.getElementById("downloadPDF").addEventListener("click", () => {
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF();

            // Get form values
            const date = document.getElementById("date_today").value || "2025-09-02";
            const driver = document.getElementById("signature").value || "John Doe";
            const truckNumber = document.getElementById("truck_number").value || "12345";
            const trailerNumber = document.getElementById("trailer1").value || "67890";
            const inspectionType = document.getElementById("pretrip").checked ? "Pre-trip" : 
                                 document.getElementById("posttrip").checked ? "Post-trip" : "Not specified";
            const condition = document.getElementById("nodefect").checked ? "No defects detected" : 
                            document.getElementById("defects").checked ? "Defects detected" : "Not specified";
            const remarks = document.getElementById("remarks").value || "No remarks";

            // ----- Encabezado -----
            pdf.setFontSize(16);
            pdf.text("Trip Inspection Report", 105, 15, { align: "center" });

            pdf.setFontSize(10);
            pdf.text(`Date: ${date}`, 15, 30);
            pdf.text(`Driver: ${driver}`, 105, 30);
            pdf.text(`Truck #: ${truckNumber}`, 15, 40);
            pdf.text(`Trailer #: ${trailerNumber}`, 105, 40);
            pdf.text(`Inspection Type: ${inspectionType}`, 15, 50);
            pdf.text(`Condition: ${condition}`, 105, 50);

            // ----- Sección de inspección -----
            pdf.setFontSize(12);
            pdf.text("Inspection Checklist", 15, 70);

            pdf.setFontSize(10);
            const checkboxes = document.querySelectorAll(".checklist:checked");
            
            let y = 80;
            checkboxes.forEach((checkbox, index) => {
                if (y > 250 && index < checkboxes.length - 1) {
                    pdf.addPage();
                    y = 20;
                    pdf.setFontSize(12);
                    pdf.text("Inspection Checklist (continued)", 15, y);
                    y += 10;
                    pdf.setFontSize(10);
                }
                
                const label = checkbox.nextElementSibling.textContent;
                pdf.rect(15, y - 4, 5, 5); // cuadro checkbox
                pdf.text("✓", 16.5, y); // check mark
                pdf.text(label, 25, y);
                y += 7;
            });

            // ----- Observaciones -----
            if (y > 200) {
                pdf.addPage();
                y = 20;
            } else {
                y += 10;
            }
            
            pdf.setFontSize(12);
            pdf.text("Remarks:", 15, y);
            pdf.setFontSize(10);
            
            // Split remarks into multiple lines if needed
            const splitRemarks = pdf.splitTextToSize(remarks, 180);
            pdf.text(splitRemarks, 15, y + 7);
            
            // Calculate height needed for remarks
            const remarksHeight = splitRemarks.length * 5;
            y += remarksHeight + 15;

            // ----- Firma -----
            if (y > 250) {
                pdf.addPage();
                y = 20;
            }
            
            pdf.setFontSize(12);
            pdf.text("Driver Signature:", 15, y);
            pdf.line(50, y, 120, y); // línea para firma
            pdf.setFontSize(10);
            pdf.text(driver, 50, y + 5);
            
            pdf.text("Date:", 15, y + 15);
            pdf.text(date, 50, y + 15);
            
            pdf.text("Time:", 15, y + 25);
            pdf.text(document.getElementById("hour_inspection").value || "N/A", 50, y + 25);

            // Guardar PDF
            pdf.save("Trip_Inspection_Report.pdf");
        });

    document.addEventListener("DOMContentLoaded", function () {
    const selectAllBtn = document.getElementById("check_all");
    const checkboxes = document.querySelectorAll(".checklist");

     let allChecked = false;

    selectAllBtn.addEventListener("click", function () {
        allChecked = !allChecked;
        checkboxes.forEach(cb => cb.checked = allChecked);
    });
});




//confirmar formulario

