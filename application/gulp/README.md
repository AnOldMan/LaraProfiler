# COMPILE INSTRUCTIONS

## PREREQUISITES

You must have Node.js installed. https:nodejs.org/en/download/
You must install gulp globally (only once). From command prompt:

```
npm install gulp -g
```

You must have libsass installed. https:www.npmjs.com/package/gulp-sass#issues

If this is the FIRST time you have worked on this library, you must initiate the installation of dependencies. From command prompt:

```
cd "/this/project/folder"
npm install
```

*Note: the path may be different or require a drive designation.
This can take some time, dependencies are installed in node_modules

If you get any "npm ERR!", this is a perms issue and you must run command prompt as administrator.

Tip: GIT Bash seems to avoid this.

1. Open Command Prompt and navigate to this directory
2. Run command "gulp css" in Command Prompt
  If you get any errors, it is likely you failed to check out one or more of the destination files for editing, see step 1.
  If you have files checked out and get error, you can fix one of two ways:
	* Undo changes, check out again, run compile
	* Navigate to directory and remove read-only property that VisualStudio placed on file[s]

## UPDATES

Every so often, you should update the packages you depend on so you can get any changes that have been made to code upstream.

To do this, run
```
npm update
```
in the same directory as your package.json file.

Test:
```
npm outdated
```
 There should not be any results.
 