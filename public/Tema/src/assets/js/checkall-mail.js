document.addEventListener('DOMContentLoaded', function() {
    'use strict';
  
    var checkAll = document.getElementById('checkAll');
    var mailListCheckboxes = document.querySelectorAll('.main-mail-list .ckbox input');
    var mailOptionsButtons = document.querySelectorAll('.main-mail-options .btn:not(:first-child)');
  
    checkAll.addEventListener('click', function() {
      if (this.checked) {
        mailListCheckboxes.forEach(function(checkbox) {
          checkbox.closest('.main-mail-item').classList.add('selected');
          checkbox.checked = true;
        });
        mailOptionsButtons.forEach(function(button) {
          button.classList.remove('disabled');
        });
      } else {
        mailListCheckboxes.forEach(function(checkbox) {
          checkbox.closest('.main-mail-item').classList.remove('selected');
          checkbox.checked = false;
        });
        mailOptionsButtons.forEach(function(button) {
          button.classList.add('disabled');
        });
      }
    });
  
    var mailItemCheckboxes = document.querySelectorAll('.main-mail-item .main-mail-checkbox input');
    mailItemCheckboxes.forEach(function(checkbox) {
      checkbox.addEventListener('click', function() {
        if (this.checked) {
          this.checked = false;
          this.closest('.main-mail-item').classList.add('selected');
          mailOptionsButtons.forEach(function(button) {
            button.classList.remove('disabled');
          });
        } else {
          this.checked = true;
          this.closest('.main-mail-item').classList.remove('selected');
          if (!document.querySelector('.main-mail-list .selected')) {
            mailOptionsButtons.forEach(function(button) {
              button.classList.add('disabled');
            });
          }
        }
      });
    });
  });
  