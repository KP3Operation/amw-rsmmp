# Contributing

[[_TOC_]]

## New to Git?

This document is not intended to serve as a "How to use Git" guide. If you are
completely new to the _Git_ version control system, there are plenty of guides,
tutorials, and primers available online. This is a good place to start:

- [Git Basics](https://git-scm.com/book/en/v1/Getting-Started-Git-Basics)
- [Learn Git](https://www.atlassian.com/git)
- [Hello World - GitHub Guide](https://guides.github.com/activities/hello-world/)
- [The Most Important Thing to Know About Git](https://youtu.be/oHg5SJYRHA0)

### About Git Examples and Git Tools

All the examples in this document will be based on the **Git Bash** command line syntax. There are a variety
of Git tools available. **Git Bash** is the format for _Gitflow_ workflow examples, because this is a universal
way to express **Git** operations. You are free to use the tooling of your choice to perform **Git**-based operations, like,
provided that you adhere to the conventions and practices outlined by this document:

- Visual Studio Code Git Integration
- Source Tree
- Git for Windows GUI
- GitHub Desktop

---

## Before you start

Before you make your first commit to this repository, please ensure that you have read and adhere to the following guidelines and conventions.
Every developer must complete the following before working with this repository:

- [x] Your local Git commit settings are properly configured
- [x] You understand the development workflow for this repository
- [x] You have reviewed and understand the the conventions for commits to this repository

[Back to Top](#contributing)

#### Check Git Commit Email

_Current Repository_

```bash
$ git config user.email
```

_Global Git Setting_

```bash
$ git config --global user.email
```

[Back to Top](#contributing)

#### Set Git Commit Email

_Current Repository_

```bash
$ git config user.email johndoe@example.com
```

_Global Git Setting_

```bash
$ git config --global user.email johndoe@example.com
```

For more information about setting your Git commit email address see [here](https://help.github.com/en/github/setting-up-and-managing-your-github-user-account/setting-your-commit-email-address#setting-your-commit-email-address-in-git).

[Back to Top](#contributing)

#### Check Git Commit Name

_Current Repository_

```bash
$ git config user.name
```

_Global Git Setting_

```bash
$ git config --global user.name
```

[Back to Top](#contributing)

#### Set Git Commit Name

_Current Repository_

```bash
$ git config user.name "John Doe"
```

_Global Git Setting_

```bash
$ git config --global user.name "John Doe"
```

For more details on your Git commit name see [here](https://help.github.com/en/github/using-git/setting-your-username-in-git).

[Back to Top](#contributing)

## Know and Follow Important Conventions

All developers are expected to follow the conventions described in this section. These conventions exist for a variety of different reasons, from increasing visibility though issue tracking integration, to aiding in the versioning of
branches and releases.

[Back to Top](#contributing)

### Git Commit Messages

[Back to Top](#contributing)

#### Message Tense

It is often convention to author _Git_ commit messages
in the present tense. I.e. describe a commit that updates logging as "Update logging" **not** "Updat**ed** logging".

From this [article](https://www.atlassian.com/git/tutorials/saving-changes/git-commit):

> Note that many developers also like to use the present tense in their commit messages. This makes them read more
> like actions on the repository, which makes many of the history-rewriting operations more intuitive.

[Back to Top](#contributing)

#### Commit Message Short Format

When a commit can be described succinctly in 50 characters or less, the commit message
should be a single line, in the form of `#Issue-Number <Short Description>`, like so:

```console
#1234 Add HomeController.HelloWorld() method
```

[Back to Top](#contributing)

#### Commit Message Descriptive Format

A common convention for _Git_ commit messages is to use the first line of the commit message as a short
(50 characters or less) summary of the commit. When more detail is required, leave a blank line, and then
provide a more detailed description.
Use this more detailed format, when a short 50 character description does not have enough detail to
adequately convey the details of the commit.

```console
#1234 Change the message displayed by Hello.php

- Update the sayHello() function to output the user's name
- Change the sayGoodbye() function to a friendlier message
```

[Back to Top](#contributing)

#### Include GitHub Issues Work Item Key in Commit Messages

All _Git_ commit messages should include the associated _Issue_ of the relevant _GitHub Issues_ Item.
This allows the _GitHub Issues_ integration with _GitHub Repositories_ to add your commit to the development section
of the _GitHub Issues_ work item. The _Issue Number_ should be at the beginning of the first line
of the commit message, in the format `#[Issue-Number]` like so:

```console
#1234 Add HomeController.HelloWorld() method
```
[Back to Top](#contributing)

## Testing

For every feature or bug fix, you should write tests to ensure that the code works as expected. The tests should be written in the same branch as the code, and should be pushed to the remote repository along with the code.

We should try to coverage the written code at least 60%.

### Unit Tests

Unit tests are written to test the functionality of a single unit of code. For example, a unit test for a method that adds two numbers together would test that the method returns the correct sum of the two numbers.

### Feature Tests

Feature tests are written to test the functionality of multiple units of code. For example, an feature test for a method that adds two numbers together would test that the method returns the correct sum of the two numbers, but it would also test that the method calls the correct methods to retrieve the two numbers.

### Running Tests

To run the tests, you may open the solution in your IDE and run the tests by using your IDE `Test Explorer`.

You can also run the tests from the command line by running the following command:

```bash
$ php artisan test
```

[Back to Top](#contributing)
