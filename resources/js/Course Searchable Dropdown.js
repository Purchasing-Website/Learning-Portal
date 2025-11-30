// Adding the keyup event listener for filtering the dropdown
document.getElementById('PGdropdownSearchInput').addEventListener('keyup', ProgramFilterDropdown);
document.getElementById('E_PGdropdownSearchInput').addEventListener('keyup', E_ProgramFilterDropdown);
document.getElementById('CSdropdownSearchInput').addEventListener('keyup', CourseFilterDropdown);

//Filter Program Dropdown
function ProgramFilterDropdown() {
          let input = document.getElementById('PGdropdownSearchInput');
          let filter = input.value.toUpperCase();
          let dropdownMenu = document.getElementsByClassName('pgdropdown')[0];
          let items = dropdownMenu.getElementsByClassName('program');
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

//Filter Program Dropdown
function E_ProgramFilterDropdown() {
          let input = document.getElementById('E_PGdropdownSearchInput');
          let filter = input.value.toUpperCase();
          let dropdownMenu = document.getElementsByClassName('e_pgdropdown')[0];
          let items = dropdownMenu.getElementsByClassName('e_program');
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



