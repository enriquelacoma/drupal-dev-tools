# What it this

Install drupal development tools for proyects using https://github.com/programeta/easy-docker-drupal

## Tools to add to a Drupal project
| Tool           | Url                                           | Description   |
|----------------|-----------------------------------------------|---------------|
| phpcs          | url                                           | code analysis |
| behat          | url                                           | code analysis |
| drupal-check   | https://github.com/mglaman/drupal-check       | code analysis |
| devel          | url                                           | code analysis |
| hacked         | https://www.drupal.org/project/hacked         | code analysis |
| twig_vardumper | https://www.drupal.org/project/twig_vardumper | code analysis |
| debug_bar      | https://www.drupal.org/project/debug_bar      | code analysis |

# How to install it

* On you project run composer require enriquelacoma/drupal-dev-tools

# How to use it

* Go to /srv/shared/drupal-dev-tools
* Configure config/config.yml
* List commands

| Command      | Description       | Hot to use it |
|--------------|-------------------|---------------|
| install-site | Install dev tools |               |
| install-dev  | Install dev tools |               |
| behat-config | Install dev tools |               |
| phpcs-config | Install dev tools |               |
