# Kirby 3 Doctor

![GitHub release](https://img.shields.io/github/release/bnomei/kirby3-doctor.svg?maxAge=1800) ![License](https://img.shields.io/github/license/mashape/apistatus.svg) ![Kirby Version](https://img.shields.io/badge/Kirby-3%2B-black.svg)

Plugin to check health of your CMS installation

## Commerical Usage

This plugin is free but if you use it in a commercial project please consider to 
- [make a donation 🍻](https://www.paypal.me/bnomei/5) or
- [buy me ☕](https://buymeacoff.ee/bnomei) or
- [buy a Kirby license using this affiliate link](https://a.paddle.com/v2/click/1129/35731?link=1170)

## Screenshots

![doctor](https://raw.githubusercontent.com/bnomei/kirby3-doctor/master/kirby3-doctor-screenshot-1.gif)

## Usage Kirby Panel

```
fields:
  doctor:
    type: doctor
    label: Perform checks
    # progress: Performing checks...
```

## Usage Kirby API (post Authentification)

```js
let doctor = fetch('https://devkit.bnomei.com/api/plugin-doctor/check')
  .then(response => response.json())
  .then(json => {
      console.log(json);
  });
```

## Provided Checks

- CheckPHPVersion (zendframework): Kirby installation requirements
- CheckPHPExtension (zendframework): Kirby installation requirements
- CheckKirbyFolders (zendframework): Read/Write-Access to core Kirby folders
- CheckComposerSecurity: https://security.symfony.com/
- CheckGitFolder: No public `.git` folder
- CheckHtaccess: Has a `.htaccess` file
- CheckKirbyAccount: Has at least one account
- CheckKirbyCacheSize: Cache folder not too big
- CheckKirbyMediaSize: Media folder not too big
- CheckKirbySystem: Kirby build-in system checks
- CheckKirbyVersion: Is Kirby up-to-date
- CheckSSL: Using `https` scheme

## Custom Checks

You can add custom checks or disable build checks using the `checks` setting in the config file.

```
return [
    'bnomei.doctor.checks' => [
        "MyNamespace\\MyClass" => true, // enable new
        "Bnomei\\CheckComposerSecurity" => false, // disable build-in
    ],
    // ...
];
```

> Contribute: You have an idea for a check? Please [create a new issue](https://github.com/bnomei/kirby3-doctor/issues/new) or submit a PR.

## Settings

All settings have to be prefixed with `bnomei.doctor.`.

**expire**
- default: `24*60` minutes to cache the results and not run tests again

**debugforce**
- default: `true` will expire the cache every time if `option('debug')` is true as well.

**checks**
- default: `[]`
- example: `["MyNamespace\\MyCheckClass" => true, "Bnomei\\CheckGitFolder" => false]`
> Attention: Namespaces must use `\\`-notion.

**log.enabled**
- default: `true` will create a log file if [Kirby Log Plugin](https://github.com/bvdputte/kirby-log) is installed as well.

**log**
- default: callback to `kirbyLog()`

## Credits

- [@bvdputte](https://github.com/bvdputte): Kirby Log Plugin
- [@jenstornell](https://github.com/jenstornell): idea of a K3 enviroment checklist Plugin

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/bnomei/kirby3-doctor/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.

