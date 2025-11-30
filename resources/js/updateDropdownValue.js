function updateDropdownText(element) {
    // Get the text of the clicked dropdown item
    const selectedText = element.innerText;
    // Find the closest dropdown button within the same parent (row or div)
    const dropdownButton = element.closest('.dropdown').querySelector('.dropdown-toggle');
 
    // Update the dropdown button's text with the selected item
    dropdownButton.innerText = selectedText;
 
    // Optionally copy inline styles like background color and text color
    dropdownButton.style.backgroundColor = window.getComputedStyle(element).backgroundColor;
    dropdownButton.style.color = window.getComputedStyle(element).color;
}
 
function selectProgram(element) {
    // Get the text of the clicked dropdown item
    const selectedText = element.innerText;
    // Find the closest dropdown button for the program within the same parent (row or div)
    const dropdownButton = element.closest('.dropdown').querySelector('.dropdown-toggle');
    //const dropdownButton = element.closest('.dropdown').querySelector('.BMdropdownMenuButton');
    // Update the dropdown button's text for the selected battery model
    dropdownButton.innerText = selectedText;
}

function selectCourse(element) {
    // Get the text of the clicked dropdown item
    const selectedText = element.innerText;
    // Find the closest dropdown button for the program within the same parent (row or div)
    const dropdownButton = element.closest('.dropdown').querySelector('.dropdown-toggle');
    //const dropdownButton = element.closest('.dropdown').querySelector('.BMdropdownMenuButton');
    // Update the dropdown button's text for the selected battery model
    dropdownButton.innerText = selectedText;
}
 
function selectSN(element) {
    // Get the text of the clicked dropdown item
    const selectedText = element.innerText;
 
    // Find the closest dropdown button for the serial number within the same parent (row or div)
    const dropdownButton = element.closest('.dropdown').querySelector('.dropdown-toggle');
 
    // Update the dropdown button's text for the selected serial number
    dropdownButton.innerText = selectedText;
}