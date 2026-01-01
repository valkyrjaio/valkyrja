# Release Notes for 25.x

## [Unreleased](https://github.com/valkyrjaio/valkyrja/compare/v25.10.0...master)

## [v25.10.0](https://github.com/valkyrjaio/valkyrja/compare/v25.9.1...v25.10.0) - 2025-12-31

* [Http] Move tests to correct namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/290
* [Test] Move tests to correct namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/291
* [CI] Add phparkitect rule for contracts to require Contract naming covention by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/292
* [CI] Update churn to 1.7.3 from 1.7.2 by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/293
* [CI] Fix release workflow after contract naming convention change by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/294

## [v25.9.1](https://github.com/valkyrjaio/valkyrja/compare/v25.9.0...v25.9.1) - 2025-12-30

* [Http] Add missing test for Middleware ServiceProvider by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/289

## [v25.9.0](https://github.com/valkyrjaio/valkyrja/compare/v25.8.0...v25.9.0) - 2025-12-30

* [CI] Add new phparkitect rule for abstract class naming by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/280
* [CI] Add new phparkitect rule for enum naming by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/281
* [CI] Update the phparkitect abstract rules by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/282
* [CI] Update and fix phparkitect rules. by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/283
* [CI] Add a rule in phparkitect for Factory namespace class naming by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/285
* [CI] Add a rule to phparkitect for Provider namespace class naming by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/286
* [Http] Splitting up the Http component provider to multiple providers by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/287
* [Cli] Split up the Cli component provider to multiple providers by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/288

## [v25.8.0](https://github.com/valkyrjaio/valkyrja/compare/v25.7.0...v25.8.0) - 2025-12-30

* [Filesystem] Use the Directory class to get the local path by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/275
* [Application] Update cache file path to use Directory class by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/276
* [View] Update dir envs to use Directory class by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/277
* [CI] Add new phparkitect rule for traits by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/278
* [CI] Add new phparkitect rule for abstract classes by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/279

## [v25.7.0](https://github.com/valkyrjaio/valkyrja/compare/v25.6.1...v25.7.0) - 2025-12-30

* [Type] Explicitly casting to an int before sprintf format to avoid warning by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/194
* [Sms] Moving Sms classes to a new Messenger namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/196
* [View] Moving Renderer classes to a new Renderer namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/195
* [Session] Moving Session classes to a new Manager namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/197
* [Orm] Moving Manager classes to a new Manager namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/198
* [Notification] Moving Notification classes to a new Manager namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/199
* [Mail] Moving Mail classes to a new Manager namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/200
* [Log] Moving Logger classes to a new Logger namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/201
* [Jwt] Moving Jwt classes to a new Manager namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/202
* [Filesystem] Moving Filesystem classes to a new Manager namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/203
* [Event] Moving Dispatcher classes to a new Dispatcher namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/204
* [Crypt] Moving Crypt classes to a new Manager namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/205
* [Event] Deprecate the Event contract and any classes that implement it by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/206
* [Cache] Moving Cache classes to a new Manager namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/207
* [Broadcast] Moving Broadcaster classes to a new Manager namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/208
* [Auth] Moving Authenticator classes to a new Authenticator namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/209
* [Attribute] Moving Attributes classes to a new Collector namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/210
* [Event] Update tests to reflect new module namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/212
* [Attribute] Update tests to reflect new module namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/211
* [Api] Moving Api classes to a new Manager namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/213
* [Mail] Update Manager namespace to Mailer by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/214
* [Http] Moving Client classes to a new Client\Manager namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/215
* [Http] Moving Router classes to a new Routing\Dispatcher namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/216
* [Http] Moving RequestHandler classes to a new Server\Handler namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/217
* [Cli] Moving Router classes to a new Routing\Dispatcher namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/218
* [Cli] Moving InputHandler classes to a new Server\Handler namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/219
* [Container] Moving Container classes to a new Manager namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/220
* [Container] Deprecate support namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/221
* [Reflection] Moving Reflection classes to a new Reflector namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/222
* [Reflection] Move ReflectionProperty to Attribute component by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/223
* [Filesystem] Fix an issue in testing service provider by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/224
* [Cli] Move Interaction Config class to Data namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/225
* [Application] Move Config class to Data namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/226
* [Application] Move Data class to Data namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/227
* [Cli] Move Routing Data class to Data namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/228
* [Application] Fix incorrect order for test assertion by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/229
* [Application] Add missing throws tag in AppTest by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/230
* [Container] Move Data class to Data namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/231
* [Event] Move Data class to Data namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/232
* [Http] Move Routing Data class to Data namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/233
* [Application] Move Env class to Env namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/234
* [Filesystem] Fix an issue with testing service provider by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/235
* [Application] Move tests to appropriate namespace to match main component by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/236
* [Container] Move data test to data namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/237
* [Cli] Move routing data test to data namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/238
* [Event] Move data test to data namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/239
* [Http] Move routing data test to data namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/240
* [Validation] Moving Validate classes to a new Validator namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/241
* [Dispatch] Change component from Dispatcher to Dispatch by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/242
* [Notification] Change Manager namespace to Notifier by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/243
* [Exception] Move ExceptionHandler to new Handler namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/244
* [Application] Move Application classes to new Kernel namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/245
* [Application] Rename Component to Provider by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/246
* [Application] Update tests with Component to Provider change by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/247
* [Type] Move the ExceptionsTest class to a new Exception namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/248
* [Throwable] Update Exception component to Throwable by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/249
* [Cli] Move Exiter class from Support component to Cli/Server by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/250
* [Support] Move time related classes to new Time namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/251
* [Support] Move Directory class to Directory namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/252
* [CI] Update Rector to import all FQNs by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/253
* [CI] Update PhpCodeSniffer to find all FQN usages by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/254
* [CI] Add new NewMethodCallWithoutParenthesesRector rector rule by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/255
* [CI] Add new CI directory for StyleCI, moving config by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/256
* [CI] Add new ExplicitNullableParamTypeRector rector rule by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/257
* [CI] Add PHP Mess Detector by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/258
* [CI] Update phparkitect to v0.7.0 by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/259
* [CI] Update psalm to v6.14.3 from v6.14.2 by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/260
* [CI] Update phpcsfixer to v3.92.3 from v3.92.0 by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/261
* [CI] Update phpcsfixer to v8.26.0 from v8.25.1. by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/262
* [CI] Update phpcsfixer composer files by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/263
* [CI] Update psalm composer files by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/264
* [CI] Update phpunit to v12.5.4 from v12.5.3 by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/265
* [CI] Update rector to v2.3.0 from v2.2.14. by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/266
* [CI] Update suggested twig/twig to v3.22.2 from v3.22.1 by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/267
* [CI] Update churn composer files by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/268
* [CI] Fix the composer outdated dependencies check on release by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/269
* [CI] Fix the release composer check command by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/270
* [CI] Fix the release composer check command by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/271
* [CI] Fix the release composer check command by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/272
* [Composer] Fix the composer checks by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/273
* [CI] Fix root composer check in the release workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/274

## [v25.6.1](https://github.com/valkyrjaio/valkyrja/compare/v25.6.0...v25.6.1) - 2025-12-27

* [Cli] Refactor the InputHandler::setInteractivity method by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/192
* [Http] Refactor the Mode::isReadable and Mode::isWriteable methods by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/193

## [v25.6.0](https://github.com/valkyrjaio/valkyrja/compare/v25.5.0...v25.6.0) - 2025-12-27

* [Type] Update exceptions to be more specific by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/183
* [Session] Update exceptions to be more specific by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/184
* [Crypt] Update exceptions to be more specific by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/185
* [Api] Update methods to take a throwable instead of exception by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/186
* [Sms] Update exceptions to be more specific by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/187
* [Http] Update route collector and add new attributes by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/188
* [Http] Fix a bug with the routing collector parameters by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/189
* [Cli] Update attribute collector and add more attributes by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/190
* [Auth] Avoid duplicate code where possible by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/191

## [v25.5.0](https://github.com/valkyrjaio/valkyrja/compare/v25.4.1...v25.5.0) - 2025-12-22

* [Http] Update data route object to require dispatch by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/180
* [Cli] Require dispatch in cli route data object by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/181
* [Http] Add value to the route parameter data object by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/182

## [v25.4.1](https://github.com/valkyrjaio/valkyrja/compare/v25.4.0...v25.4.1) - 2025-12-22

* [Application] Add custom component to env by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/179

## [v25.4.0](https://github.com/valkyrjaio/valkyrja/compare/v25.3.4...v25.4.0) - 2025-12-20

* [Cli] Fix version command typo by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/175
* [Application] Add capability to env to turn off certain components by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/178

## [v25.3.4](https://github.com/valkyrjaio/valkyrja/compare/v25.3.3...v25.3.4) - 2025-12-18

* [CI] Add MST to release build date by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/174

## [v25.3.3](https://github.com/valkyrjaio/valkyrja/compare/v25.3.2...v25.3.3) - 2025-12-17

* [CI] Fix build date update step by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/173

## [v25.3.2](https://github.com/valkyrjaio/valkyrja/compare/v25.3.1...v25.3.2) - 2025-12-17

* [Application] Add build date along with version by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/172

## [v25.3.1](https://github.com/valkyrjaio/valkyrja/compare/v25.3.0...v25.3.1) - 2025-12-17

* [Exception] Add tests for the exception module by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/154
* [CI] Cleanup the release workflow for better readability by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/155
* [CI] Add new workflow to create major version branch by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/156
* [CI] Fix major version branch workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/157
* [CI] More fixes for the major version branch create workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/158
* [CI] Fix major version branch create workflow changelog step by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/159
* [CI] Add cherry-pick workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/160
* [Documentation] Update contributing with more info by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/161
* [Deprecation] Removing unused ConfigClass file in tests by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/162
* [Documentation] Add more commit message tag examples by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/163
* [CI] Deprecate and remove the merge workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/164
* [CI] Use new LATEST_MAJOR_VERSION environment var by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/165
* [CI] Fix an issue with the create version workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/166
* [CI] Fix name in create version branch workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/167
* [CI] Fix update var via cli step in create version branch workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/168
* [CI] Use dedicated action for GH variable update by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/169
* [Revert] "[CI] Use dedicated action for GH variable update" by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/170
* [CI] Update the unrelease url in create-version-branch workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/171

## [v25.3.0](https://github.com/valkyrjaio/valkyrja/compare/v25.2.19...v25.3.0) - 2025-12-17

* [CI] Add a workflow to merge major version up by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/132
* [Documentation] Update badges in README for 25.x branch by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/133
* [CI] Add a check in merge workflow to not allow master by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/134
* [CI] Update workflows to work on major branches by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/135
* [CI] Fix the merge major version workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/136
* [CI] Fix readme update after merge by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/137
* [Documentation] Update badges in README for 25.x branch by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/138
* [Type] Add missing tests by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/148
* [Documentation] Update the Coveralls badge by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/149
* [Documentation] Update the Coveralls badge svg to master by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/150
* [Documentation] Update the Scrutinizer badge svg to master by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/151
* [Dispatcher] Update arguments to require argument name by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/152
* [CI] Update the composer check to only check direct dependencies by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/153

## [v25.2.19](https://github.com/valkyrjaio/valkyrja/compare/v25.2.18...v25.2.19) - 2025-12-14

* [Documentation] Update README badges to point to master by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/130
* [CI] Add major version as an output for version check step by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/131

## [v25.2.18](https://github.com/valkyrjaio/valkyrja/compare/v25.2.17...v25.2.18) - 2025-12-14

* [CI] Fix versioning in release workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/129

## [v25.2.17](https://github.com/valkyrjaio/valkyrja/compare/v25.2.16...v25.2.17) - 2025-12-14

* [Documentation] Updating the security documentation by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/99
* [Documentation] Update security documentation formatting by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/100
* [Documentation] Update code of conduct by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/101
* [Documentation] Update the pull request template by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/102
* [CI] Add failure comment for phpcsfixer and rector workflows by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/103
* [CI] Update, and standardize, workflows formatting by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/104
* [Documentation] Fix a typo in a comment in the pull request template by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/105
* [Documentation] Update contributing documentation by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/106
* [CI] Add a PR comment on composer validation failure by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/107
* [CI] Update composer validation to only run on minimum supported PHP version by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/108
* [CI] Add a PR comment on psalm failure by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/109
* [CI] Add a period to the end of PR comment by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/110
* [CI] Remove an unused check for PHP 8.6 in composer install step by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/111
* [CI] Add a PR comment on phpunit failure by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/112
* [CI] Update PR comment for phpunit only on failure by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/113
* [CI] Add a PR comment on phpstan failure by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/114
* [CI] Add a PR comment on php code sniffer failure by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/115
* [CI] Add a PR comment on phparkitect failure by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/116
* [CI] Fix incorrect paths for paths filter in rector workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/117
* [CI] Add the name to distinguish more easily which check failed by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/118
* [CI] Add a new commit message check workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/119
* [Functions] Change parameter name in dd function by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/120

## [v25.2.16](https://github.com/valkyrjaio/valkyrja/compare/v25.2.15...v25.2.16) - 2025-12-13

* [CI] Fixing path filtering by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/98

## [v25.2.15](https://github.com/valkyrjaio/valkyrja/compare/v25.2.14...v25.2.15) - 2025-12-13

* [CI] Fixing path filtering by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/97

## [v25.2.14](https://github.com/valkyrjaio/valkyrja/compare/v25.2.13...v25.2.14) - 2025-12-13

* [CI] Update all workflows' OS and PHP by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/81
* [CI] Adding the Scrutinizer CI config to the repo for better control by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/82
* [CI] Update pre-commit hooks by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/83
* [Documentation] Add date to LICENSE by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/84
* [CI] Validate all composer files by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/85
* [Functions] Update the dd() helper function by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/86
* [CI] Fix path filtering and still allow required checks to run by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/87
* [CI] Fix steps order in validate-composer workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/88
* [CI] Fix steps order in workflows by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/89
* [CI] Add paths-filter to all workflows by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/90
* [Documentation] Add build status for validate composer workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/91
* [Git] Remove outdated files and directories from .gitignore by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/92
* [CI] Removing random whitespace in release workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/93
* [Editor] Update .editorconfig end_of_line to `lf` from `LF` by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/94
* [CI] Update StyleCI config to include tests by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/95
* [Documentation] Fix changelog urls by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/96

## [v25.2.13](https://github.com/valkyrjaio/valkyrja/compare/v25.2.12...v25.2.13) - 2025-12-13

* [CI] Update release workflow names by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/80

## [v25.2.12](https://github.com/valkyrjaio/valkyrja/compare/v25.2.11...v25.2.12) - 2025-12-13

* [CI] Cleanup release guard steps by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/79

## [v25.2.11](https://github.com/valkyrjaio/valkyrja/compare/v25.2.10...v25.2.11) - 2025-12-13

* [CI] Update get tag debug by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/77
* [CI] Use api tags for version guard by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/78

## [v25.2.10](https://github.com/valkyrjaio/valkyrja/compare/v25.2.9...v25.2.10) - 2025-12-13

* [CI] Update to use action to get tags by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/76

## [v25.2.9](https://github.com/valkyrjaio/valkyrja/compare/v25.2.8...v25.2.9) - 2025-12-13

* [CI] Update debug for version guard step in release workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/74
* [CI] Update debug for version guard step in release workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/75

## [v25.2.8](https://github.com/valkyrjaio/valkyrja/compare/v25.2.7...v25.2.8) - 2025-12-13

* [CI] Update debug for version guard step in release workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/73

## [v25.2.7](https://github.com/valkyrjaio/valkyrja/compare/v25.2.6...v25.2.7) - 2025-12-13

* [CI] Add debug for version guard step in release workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/72

## [v25.2.6](https://github.com/valkyrjaio/valkyrja/compare/v25.2.5...v25.2.6) - 2025-12-13

* [CI] Update release workflow name by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/71

## [v25.2.5](https://github.com/valkyrjaio/valkyrja/compare/v25.2.4...v25.2.5) - 2025-12-13

* [CI] Update release workflow names by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/70

## [v25.2.4](https://github.com/valkyrjaio/valkyrja/compare/v25.2.3...v25.2.4) - 2025-12-13

* [CI] Use run-name instead of name for dynamic name by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/69

## [v25.2.3](https://github.com/valkyrjaio/valkyrja/compare/v25.2.2...v25.2.3) - 2025-12-13

* [CI] Fix the changelog release commit message by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/67
* [CI] Fix the logic to check if a version already exists by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/68

## [v25.2.2](https://github.com/valkyrjaio/valkyrja/compare/v25.2.1...v25.2.2) - 2025-12-13

* [CI] Add version and branch matching safeguard to release workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/63
* [CI] Standardize release commit messages by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/64
* [CI] Fix the name of the date step by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/65
* [CI] Ensure the release version input doesn't already exist by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/66

## [v25.2.1](https://github.com/valkyrjaio/valkyrja/compare/v25.2.0...v25.2.1) - 2025-12-13

* [Git] Updating gitattributes by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/61
* [CI] Update the VERSION.md file on release by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/62

## [v25.2.0](https://github.com/valkyrjaio/valkyrja/compare/v25.1.6...v25.2.0) - 2025-12-13

* [Composer] Adding a bin and entry point to run commands within the fr… by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/58
* [Composer] Updating homepage to https. by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/59
* [Composer] Removing suggested packages from require-dev by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/60

## [v25.1.6](https://github.com/valkyrjaio/valkyrja/compare/v25.1.5...v25.1.6) - 2025-12-12

* [Docs] Add a guide for how to release with commits using a GitHub App… by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/56
* [GHA] Remove unused action. by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/57

## [v25.1.5](https://github.com/valkyrjaio/valkyrja/compare/25.1.4...v25.1.5) - 2025-12-12

* [CI] Update release workflow to include changelog updates. by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/52
* [CI] Update release workflow to include changelog updates. by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/53
* [CI] Update release workflow to include changelog updates. by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/54
* Fix changelog and release notes by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/55

## [25.1.4](https://github.com/valkyrjaio/valkyrja/compare/v25.1.3...25.1.4) - 2025-12-12

* [CI] Use Valkyrja GHA Bot token by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/49
* [CI] Need to figure out GitHub app token for Valkyrja GHA Bot. by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/50
* [CI] Use Valkyrja GHA App token. by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/51

## [v25.1.3](https://github.com/valkyrjaio/valkyrja/compare/v25.1.2...v25.1.3) - 2025-12-12

* [CI] Update release-new-version.yml application version commit messag… by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/48

## [v25.1.2](https://github.com/valkyrjaio/valkyrja/compare/v25.1.1...v25.1.2) - 2025-12-12

* [Doc] Fix incorrect date for v25.1.1 by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/47

## [v25.1.1](https://github.com/valkyrjaio/valkyrja/compare/v25.1.0...v25.1.1) - 2025-12-12

* [Docs] Update CHANGELOG.md for v25.0.0 by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/45
* [Doc] Fix incorrect date for v25.1.0 by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/46

## [v25.1.0](https://github.com/valkyrjaio/valkyrja/compare/v25.0.0...v25.1.0) - 2025-12-12

* Documentation: Update all documentation. by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/40
* Cli: Updating routing; change Command to Route, Controller to Command… by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/39
* [Http] Update Parameter to be allowed on methods. by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/41
* [CI] Fix duplicate identifier. by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/42
* [Dispatcher] Update dispatch dependencies to include the parameter/ar… by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/43
* [CI] Deprecate and remove CodeCov. by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/44

## [v25.0.0](https://github.com/valkyrjaio/valkyrja/compare/v3.0.0...v25.0.0) - 2025-12-11

* Annotation: Deprecate annotation component. by @MelechMizrachi in https://github.com/valkyrjaio/valkyrja/pull/35
* Auth: New Adapter paradigm. by @MelechMizrachi in https://github.com/valkyrjaio/valkyrja/pull/36
* Apply fixes from StyleCI by @MelechMizrachi in https://github.com/valkyrjaio/valkyrja/pull/37
* Config update by @MelechMizrachi in https://github.com/valkyrjaio/valkyrja/pull/38
