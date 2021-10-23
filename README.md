# Kirby 3 Doctor

![Release](https://flat.badgen.net/packagist/v/bnomei/kirby3-doctor?color=ae81ff)
![Downloads](https://flat.badgen.net/packagist/dt/bnomei/kirby3-doctor?color=272822)
[![Build Status](https://flat.badgen.net/travis/bnomei/kirby3-doctor)](https://travis-ci.com/bnomei/kirby3-doctor)
[![Coverage Status](https://flat.badgen.net/coveralls/c/github/bnomei/kirby3-doctor)](https://coveralls.io/github/bnomei/kirby3-doctor) 
[![Maintainability](https://flat.badgen.net/codeclimate/maintainability/bnomei/kirby3-doctor)](https://codeclimate.com/github/bnomei/kirby3-doctor)  
[![Twitter](https://flat.badgen.net/badge/twitter/bnomei?color=66d9ef)](https://twitter.com/bnomei)

Plugin to check health of your CMS installation

## Commercial Usage

> <br>
> <b>Support open source!</b><br><br>
> This plugin is free but if you use it in a commercial project please consider to sponsor me or make a donation.<br>
> If my work helped you to make some cash it seems fair to me that I might get a little reward as well, right?<br><br>
> Be kind. Share a little. Thanks.<br><br>
> &dash; Bruno<br>
> &nbsp; 

| M | O | N | E | Y |
|---|----|---|---|---|
| [Github sponsor](https://github.com/sponsors/bnomei) | [Patreon](https://patreon.com/bnomei) | [Buy Me a Coffee](https://buymeacoff.ee/bnomei) | [Paypal dontation](https://www.paypal.me/bnomei/15) | [Buy a Kirby license using this affiliate link](https://a.paddle.com/v2/click/1129/35731?link=1170) |

## Installation

- unzip [master.zip](https://github.com/bnomei/kirby3-doctor/archive/master.zip) as folder `site/plugins/kirby3-doctor` or
- `git submodule add https://github.com/bnomei/kirby3-doctor.git site/plugins/kirby3-doctor` or
- `composer require bnomei/kirby3-doctor`

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
let doctor = fetch('https://kirby3-plugins.bnomei.com/api/plugin-doctor/check')
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
- CheckKirbyLicense: License exists (on non localhost)
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

You can also use your own plugin to define checks (since 1.2.0). Many thanks to @fabianmichael for the great idea.
```
Kirby::plugin('my/plugin', [        // your plugin
  'bnomei.doctor.checks' => [       // required option id
    "MyNamespace\\MyClass" => true, // enable new
  ],
]);
```

> Contribute: You have an idea for a check or a plugin defining a check? Please [create a new issue](https://github.com/bnomei/kirby3-doctor/issues/new) or submit a PR.

## Settings

| bnomei.doctor.           | Default        | Description               |            
|--------------------------|----------------|---------------------------|
| expire | `24*60` | minutes to cache the results and not run tests again |
| debugforce | `true` | will expire the cache every time if `option('debug')` is true as well. |
| checks | `[]` | example: `["MyNamespace\\MyCheckClass" => true, "Bnomei\\CheckGitFolder" => false]` Attention: Namespaces must use `\\`-notion. |
| log.enabled | `true` | will create a log file if [Kirby Log Plugin](https://github.com/bvdputte/kirby-log) is installed as well. |
| log | `callback` | to `kirbyLog()` |

## Credits

- [@bvdputte](https://github.com/bvdputte): Kirby Log Plugin
- [@jenstornell](https://github.com/jenstornell): idea of a K3 enviroment checklist Plugin

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/bnomei/kirby3-doctor/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.

