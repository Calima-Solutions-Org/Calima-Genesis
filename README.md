<p align="center">
    <img title="Genesis by Calima" height="100" src="https://genesis.by.calimasolutions.com/assets/genesis-by-calima.png" />
</p>

Genesis is a CLI tool built by [Calima](https://calimasolutions.com) for their own workflow. It's a CLI tool that provides an elegant and automated starting point for your project, as well as providing different modules that can be installed in your application to make development easier and faster.

## Requirements
- PHP8.0+ is required
## Installation
Genesis can be installed with Composer by running the following command:

```bash
composer global require calima-solutions/calima-genesis
```

### Updating

To update the CLI tool, just run `genesis self-update` and it will download a newer version if your local copy is not updated.

## Authentication
Once Genesis is installed, you can log in with the credentials provided by Calima using the following command:

```bash
genesis authenticate
```

You will be prompted for your email and password, and a token will be stored in your computer for faster access.

## Creating a new project
Genesis provides a quick starting point for both back-end and front-end projects right from the CLI. Just run `genesis start <project>` and follow the wizard to create the project with Calima standards.

### Available projects

#### Laravel

Run `genesis start laravel` and Genesis will guide you through the process. The following elements are optional and you will be prompted during the installation:

- [Filament](https://filamentphp.com): An admin panel based on the TALL Stack
- [Breeze](https://github.com/laravel/breeze): Provides authentication and installs TailwindCSS
- [Actions](https://laravelactions.com/): A package that uses a new way of organising the logic of your Laravel applications by focusing on the actions your application provide

Apart from this, this command will install the following elements:

- [Pint](https://github.com/laravel/pint): Code formatter following Laravel standards
- [Laravel Log Viewer](https://github.com/opcodesio/log-viewer): A beautiful log viewer, already protected so it can only be seen by Calima employees
- [Larastan](https://github.com/nunomaduro/larastan): Static code analysis for Laravel
- Pull Request template: a PR template following the standard in Calima

#### Ionic App

Run `genesis start ionic` and Genesis will guide you through the process. The following elements are optional and you will be prompted during the installation:

- [Tailwind](https://tailwindcss.com): CSS Framework for fast prototyping
- API Service: Provides a starting point for using an API in your app

## Custom commands
Genesis is not only a great tool for starting a project, but also to use it when a project is already running. Custom commands automate some actions that are common during our daily work:

- Installation of useful packages
- Execution of tests
- Linting the code
- And many more things coming

### Listing custom commands
To list the available commands, execute the following line on the CLI:

```bash
genesis commands
```

### Executing a custom command
Once you find the command you were looking for, you can execute it like this:

```bash
genesis run <signature>
```

For example, if I wanted to run `install:tailwind-angular` on my Ionic app, I would run:

```bash
genesis run install:tailwind-angular
```

This command would install TailwindCSS with the [Typography](https://tailwindcss.com/docs/typography-plugin) and [Forms](https://github.com/tailwindlabs/tailwindcss-forms) packages, create the `tailwind.config.js` file with a primary and secondary colors and add the `@tailwind` calls on the CSS file.

## Modules
Modules are Genesis' way of sharing code between projects. When we create an integration or a tool, we can convert it to a module and it can be used by all our Calima colleagues in their projects.

Each module can have different *flavors* for each technology or integration type. For example, if we were building a module for a blogging system it could have the following flavors:

- Blade
- Inertia Vue
- Inertia React
- Livewire
- Filament panel
- API

Although it would be ideal, not every module will have all the flavors available. Genesis provides a quick access to all the modules and their flavors.

### Listing modules
Run `genesis modules` to print a list of the available modules on the console. The columns **Module ID** and **Versions** are specially important, as they are the ones used to install a module afterwards.

### Installing a module
To install a module, you can execute the following command:

```bash
genesis module {identifier?} {flavor?}
```

If you already know the identifier and flavor you want to install, you can directly add it to the command. If you don't know either, you can just run `genesis module` and the CLI will help you with some autocompletion.

## Creating modules
The process of creating modules is still pending to be documented, although the requirements for a module to be accepted are defined in the following lines.

### Requirements and guidelines
- Flavors should be as small as possible (ej. one module for the migrations and models, another one for the admin panel, another one for Blade views, another one for API...) so they can be stacked
- The module code should be transferred to `calima-solutions` GitHub account so that Genesis has access
- The module should be placed inside a brand-new project so we're sure it can run isolated
- Back-end code must be tested with Unit / Feature tests where possible, and tests must be passing. If tests cannot be provided, the README should provide information on how to test it
- It should have a README.md file explaining how enough documentation on how it can be used
