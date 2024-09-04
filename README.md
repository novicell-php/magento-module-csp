# Novicell_Csp
Module for Magento that allows inserting links in the backend that will be allowed by the csp security.

## Installation ##

1. Create the module folder, while standing in your root magento project folder
    *  `mkdir -p app/code/Novicell/Csp`
2. Enter the Novicell namespace folder
    *  `cd app/code/Novicell`
3. Upgrade Magento, DB and schema
    *  `magerun2 module:enable Novicell_Csp && magerun2 setup:upgrade && magerun2 setup:di:compile`

## Contributions ##

Feel free to contribute to this module. Please create a pull request and we will review it as soon as possible.
