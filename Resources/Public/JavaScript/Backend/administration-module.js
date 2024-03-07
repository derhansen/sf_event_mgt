import DateTimePicker from '@typo3/backend/date-time-picker.js';
import '@typo3/backend/input/clearable.js';

class AdministrationModule {
  constructor() {
    var dateTimePickerElements = document.querySelectorAll('.t3js-datetimepicker');
    var clearableElements = document.querySelectorAll('.t3js-clearable');
    dateTimePickerElements.forEach((dateTimePickerElement) => {
      DateTimePicker.initialize(dateTimePickerElement)
    })
    clearableElements.forEach((clearableField) => {
      clearableField.clearable()
    })
  }
}

export default new AdministrationModule();
