// Adding the keyup event listener for filtering the dropdown
document.getElementById('CSdropdownSearchInput').addEventListener('keyup', CourseFilterDropdown);
document.getElementById('E_CSdropdownSearchInput').addEventListener('keyup', E_CourseFilterDropdown);


//Filter Course Dropdown
function CourseFilterDropdown() {
          let input = document.getElementById('CSdropdownSearchInput');
          let filter = input.value.toUpperCase();
          let dropdownMenu = document.getElementsByClassName('csdropdown')[0];
          let items = dropdownMenu.getElementsByClassName('course');
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

//Filter Course Dropdown In Edit Form
function E_CourseFilterDropdown() {
          let input = document.getElementById('E_CSdropdownSearchInput');
          let filter = input.value.toUpperCase();
          let dropdownMenu = document.getElementsByClassName('e_csdropdown')[0];
          let items = dropdownMenu.getElementsByClassName('e_course');
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

