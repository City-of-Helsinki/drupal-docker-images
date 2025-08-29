function helfi_check_packages {
  composer show "drupal/helfi_*" $@
  composer show "drupal/hdbt*" $@
}
alias helfi_check_package_status='helfi_check_packages --outdated'
