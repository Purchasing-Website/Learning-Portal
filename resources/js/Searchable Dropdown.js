// Adding the keyup event listener for filtering the dropdown
document.getElementById('PGdropdownSearchInput').addEventListener('keyup', BMfilterfirstBMDropdown);
document.getElementById('SNdropdownSearchInput').addEventListener('keyup', filterfirstSNDropdown);

//Filter First Battery Model Dropdown
function BMfilterfirstBMDropdown() {
          let input = document.getElementById('PGdropdownSearchInput');
          let filter = input.value.toUpperCase();
          let dropdownMenu = document.getElementsByClassName('bmdropdown')[0];
          let items = dropdownMenu.getElementsByClassName('batteryModel');
          console.log(input.value);
          // Loop through all dropdown items, and hide those that don't match the search query
          console.log(dropdownMenu); // Check if dropdownMenu is correctly selected
console.log(items); // Check if items are correctly selected

          if (!items || items.length === 0) {
    console.log('No dropdown items found!');
    return;
}
          for (let i = 0; i < items.length; i++) {
              console.log(input.value);
              let txtValue = items[i].textContent || items[i].innerText;
              if (txtValue.toUpperCase().indexOf(filter) > -1) {
                  items[i].style.display = "";
              } else {
                  items[i].style.display = "none";
              }
          }
      }

//Filter First Serial Number Dropdown
function filterfirstSNDropdown() {
          let input = document.getElementById('SNdropdownSearchInput');
          let filter = input.value.toUpperCase();
          let dropdownMenu = document.getElementsByClassName('sndropdown')[0];
          let items = dropdownMenu.getElementsByClassName('serialnumber');
          console.log(input.value);
          // Loop through all dropdown items, and hide those that don't match the search query
          console.log(dropdownMenu); // Check if dropdownMenu is correctly selected
console.log(items); // Check if items are correctly selected

          if (!items || items.length === 0) {
    console.log('No dropdown items found!');
    return;
}
          for (let i = 0; i < items.length; i++) {
              console.log(input.value);
              let txtValue = items[i].textContent || items[i].innerText;
              if (txtValue.toUpperCase().indexOf(filter) > -1) {
                  items[i].style.display = "";
              } else {
                  items[i].style.display = "none";
              }
          }
      }
//Only for first dropdown


// Function to filter the dropdown for each serial number input from newly added dropdown
function filterDropdown(counter) {
    let input = document.getElementById(`dropdownSearchInput${counter}`);
    let filter = input.value.toUpperCase();
    let dropdownMenu = input.closest('.dropdown-menu');
    let items = dropdownMenu.getElementsByClassName('serialnumber');
    
    if (!items || items.length === 0) {
        console.log('No dropdown items found!');
        return;
    }
    
    for (let i = 0; i < items.length; i++) {
        let txtValue = items[i].textContent || items[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            items[i].style.display = "";
        } else {
            items[i].style.display = "none";
        }
    }
}

function filterBMDropdown(counter) {
    let input = document.getElementById(`PGdropdownSearchInput${counter}`);
    let filter = input.value.toUpperCase();
    let dropdownMenu = input.closest('.dropdown-menu');
    let items = dropdownMenu.getElementsByClassName('dropdown-item');
    
    if (!items || items.length === 0) {
        console.log('No dropdown items found!');
        return;
    }
    
    for (let i = 0; i < items.length; i++) {
        let txtValue = items[i].textContent || items[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            items[i].style.display = "";
        } else {
            items[i].style.display = "none";
        }
    }
}

// function selectSN(item) {
//     let dropdownButton = item.closest('.dropdown').querySelector('.dropdown-toggle');
//     dropdownButton.textContent = item.textContent;
// }
