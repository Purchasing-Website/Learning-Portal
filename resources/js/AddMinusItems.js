
//Add Order Items
let serialCounter = 1;  // Counter to give each serial number a unique ID
 
function addSerialNumber(button) {
    // Find the closest serial number section
    let serialNumberSection = button.closest('.serial-number-section');
 
					  
    // Create new input field for serial number
    let newSerialInput = document.createElement('div');
    newSerialInput.classList.add('row');
    newSerialInput.classList.add('serial-number-row');
    newSerialInput.style.marginTop ="5px";
    newSerialInput.innerHTML = `
<div class="col serial-number-section">
<div class="row" style="margin-top: 0px;">
<div class="col-2 col-xxl-1" style="text-align: center;padding: 0px;"><button class="btn btn-outline-primary btn-sm" type="button" style="background: rgb(255,255,255);padding: 4px 4px;margin: 0px;margin-right: 0px;display: inline-block;height: 34px;border-width: 1px;border-color: rgba(0,0,0,0);border-radius: 50%;width: 34px;text-align: center;" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-1" onclick="removeSerialNumber(this)"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none" class="mb-1" style="color: rgb(0,0,0);display: block;margin: 0px 0px;font-size: 24px;">
<path d="M18 12H6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
</svg></button></div>
<div class="col" style="padding: 0px 5px;">
<div class="dropdown" style="display: block;margin-left: 0px;width: 100%;"><button class="btn btn-primary dropdown-toggle text-end form-control" aria-expanded="false" data-bs-toggle="dropdown" id="SNdropdownMenuButton${serialCounter}" type="button" style="background: rgb(255,255,255);color: rgb(0,0,0);width: 100%;border-color: rgba(163,162,162,0);">Serial Number</button>
<div class="dropdown-menu sndropdown" style="width: 100%;"><input type="text" id="dropdownSearchInput${serialCounter}" class="form-control" onkeyup="filterDropdown(${serialCounter})"><a class="dropdown-item serialnumber" href="#" onclick="selectSN(this)">SN001</a><a class="dropdown-item serialnumber" href="#" onclick="selectSN(this)">SN002</a><a class="dropdown-item serialnumber" href="#" onclick="selectSN(this)">SN003</a></div>
</div>
</div>
</div>
</div>
  `;
 
    // Increment the counter for the next serial number
    serialCounter++;
 
    // Append new serial input to the section
    serialNumberSection.appendChild(newSerialInput);
}

let itemCounter = 1;  // Counter to give each battery model and serial number a unique ID
 
function addNewItem(button) {
    // Get the order section container
    let orderSection = document.getElementById('order-section');
 
    // Create a new item section (product dropdown + serial number fields)
    let newItemSection = document.createElement('div');
    newItemSection.classList.add('row');
    newItemSection.classList.add('battery-row');
    newItemSection.style.marginTop = "12px";
    newItemSection.innerHTML = `
<div class="col">
<div class="row">
<div class="col-2 col-xxl-1" style="text-align: center;padding: 0px;"><button class="btn btn-outline-primary btn-sm" type="button" style="background: rgb(255,255,255);padding: 4px 4px;margin: 0px;margin-right: 0px;display: inline-block;height: 34px;border-radius: 50%;border-width: 1px;border-color: rgba(0,0,0,0);" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-1" onclick="removeBatteryModel(this)"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none" class="mb-1" style="color: rgb(0,0,0);display: block;margin: 0px 0px;font-size: 24px;"><path d="M18 12H6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></button></div>
<div class="col" style="padding: 0px 5px;">
<div class="dropdown" style="display: block;margin-left: 0px;width: 100%;"><button class="btn btn-primary dropdown-toggle text-end form-control" aria-expanded="false" data-bs-toggle="dropdown" id="BMdropdownMenuButton${itemCounter}" type="button" style="background: rgb(255,255,255);color: rgb(0,0,0);width: 100%;border-color: rgba(163,162,162,0);"><span style="color: var(--bs-dropdown-link-hover-color); background-color: var(--bs-dropdown-link-hover-bg);">Battery Model</span></button>
<div class="dropdown-menu bmdropdown" style="width: 100%;"><input type="text" id="BMdropdownSearchInput${itemCounter}" class="form-control search" onkeyup="filterBMDropdown(${itemCounter})"><a class="dropdown-item" href="#" onclick="selectBatteryModel(this)">Amaron Hi Life 42B20L&nbsp;– 12V, 750 CCA (RM300.00)&nbsp;</a><a class="dropdown-item" href="#" onclick="selectBatteryModel(this)">Amaron Hi Life 55B24L/R – 12V, 600 CCA (RM270.00) </a><a class="dropdown-item" href="#" onclick="selectBatteryModel(this)">Amaron Hi Life 65D26 – 12V, 650 CCA (RM240.00) </a></div>
</div>
</div>
</div>
</div>
<div class="col serial-number-section">
<div class="row" style="margin-top: 0px;">
<div class="col-2 col-xxl-1" style="text-align: center;padding: 0px;"><button class="btn btn-outline-primary btn-sm" type="button" style="background: rgb(255,255,255);padding: 4px 4px;margin: 0px;margin-right: 0px;display: inline-block;height: 34px;border-width: 1px;border-color: rgba(0,0,0,0);border-radius: 50%;width: 34px;text-align: center;" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-1" onclick="addSerialNumber(this)"><i class="material-icons mb-1" style="color: rgb(0,0,0);display: block;margin: 0px 0px;font-size: 24px;">add</i></button></div>
<div class="col" style="padding: 0px 5px;">
<div class="dropdown" style="display: block;margin-left: 0px;width: 100%;"><button class="btn btn-primary dropdown-toggle text-end form-control" aria-expanded="false" data-bs-toggle="dropdown" id="SNdropdownMenuButton${serialCounter}" type="button" style="background: rgb(255,255,255);color: rgb(0,0,0);width: 100%;border-color: rgba(163,162,162,0);">Serial Number</button>
<div class="dropdown-menu sndropdown" style="width: 100%;"><input type="text" id="dropdownSearchInput${serialCounter}" class="form-control" onkeyup="filterDropdown(${serialCounter})"><a class="dropdown-item serialnumber" href="#" onclick="selectSN(this)">SN001</a><a class="dropdown-item serialnumber" href="#" onclick="selectSN(this)">SN002</a><a class="dropdown-item serialnumber" href="#" onclick="selectSN(this)">SN003</a></div>
</div>
</div>
</div>
</div>
`;
 
    // Increment the counter for the next item
    itemCounter++;
    serialCounter++;
 
    // Append the new item section to the order section
    orderSection.appendChild(newItemSection);
}

function removeBatteryModel(button) {
    // Find the parent row of the button clicked and remove it
    var row = button.closest('.battery-row');
    if (row) {
        row.remove();
    }
}

function removeSerialNumber(button) {
    // Find the parent row of the button clicked and remove it
    var row = button.closest('.serial-number-row');
    if (row) {
        row.remove();
    }
}