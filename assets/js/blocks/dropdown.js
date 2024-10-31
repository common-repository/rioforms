
function dropdownSettings( selectElem ) {
	const isSearch = selectElem.getAttribute("data-searchable") === 'true';
		const placeholderText = selectElem.getAttribute("placeholder");
		const isMultiple = selectElem.getAttribute("data-multiple") === 'true';

		if( isMultiple ) {
			selectElem.setAttribute("multiple", "multiple");
		}

		const handleMultiInput = () => {
			if( ! isMultiple ) {
				return '';
			}

			const closeIcon = '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 4L4 12" stroke="currentColor" stroke-opacity="0.65" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path><path d="M4 4L12 12" stroke="currentColor" stroke-opacity="0.65" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path></svg>';

			let plugins = {
				'remove_button':{
					label: closeIcon
				},
				'clear_button':{
					'html':function(data){
						return `<div class="${data.className}" title="${data.title}">${closeIcon}</div>`;
					}
				}
			}

			if( isSearch ) {
				plugins = {
					...plugins,
					'dropdown_input': 'dropdown_input'
				}
			}

			return {
				plugins: {
					...plugins
				}
			}
		}

		const handleControlInput = () => {
			if( isSearch ) {
				return {
					plugins: ['dropdown_input'],
				};
			}

			return {
				controlInput: false,
				allowEmptyOption: false,
			}
		}

		const settings = {
			create: false,
			hideSelected: false,
			closeAfterSelect: true,
			...handleControlInput(),
			...handleMultiInput(),
			onInitialize: function() {
				this.wrapper.classList.remove('rio-form-select');
				this.control.classList.add('rio-form-select');
				this.control_input.classList.add('rio-search-input');
			}
		}

		const tomSelectInstance = new TomSelect(selectElem, settings );

		const placeholderEl = document.createElement('span');
		placeholderEl.className = 'rio-forms-placeholder';
		placeholderEl.textContent = placeholderText;

		const controlElement = tomSelectInstance.control.closest('.ts-control');
		controlElement.insertBefore(placeholderEl, controlElement.firstChild);
}

function initializeDropdown() {
	const selectElems = document.querySelectorAll('.rio-form-select');

	if( selectElems ) {
		[...selectElems].forEach(function(selectElem) {
			dropdownSettings(selectElem);
		})
	}
}

document.addEventListener("DOMContentLoaded", initializeDropdown);
