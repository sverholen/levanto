/**
 * 
 */

function checkContactDetails(element, prefix) {
	if (!prefix) {
		prefix = '';
	}
	
	toggleElement(element, prefix + 'email');
	toggleElement(element, prefix + 'phone');
	toggleElement(element, prefix + 'fax');
	toggleElement(element, prefix + 'cell');
	toggleElement(element, prefix + 'website');
}

function checkAddress(element, prefix) {
	if (!prefix) {
		prefix = '';
	}
	
	toggleElement(element, prefix + 'street');
	toggleElement(element, prefix + 'number');
	toggleElement(element, prefix + 'box');
	toggleElement(element, prefix + 'postal_code');
	toggleElement(element, prefix + 'city');
	toggleElement(element, prefix + 'country');
}

function toggleElement(checkbox, element) {
	isEnabled = document.getElementById(checkbox).checked;
	
	if (isEnabled) {
		disableElement(element);
	}
	else {
		enableElement(element);
	}
}

function enableElement(element) {
	document.getElementById(element).enabled = true;
	document.getElementById(element).disabled = false;
	
	if (document.getElementById(element).label) {
		document.getElementById(element).label.className = '';
	}
}

function disableElement(element) {
	document.getElementById(element).enabled = false;
	document.getElementById(element).disabled = true;
	
	if (document.getElementById(element).label) {
		document.getElementById(element).label.className = 'disabledlabel';
	}
}