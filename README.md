moodle-availability_xapiactivity
================================

Restrict module and section access based on an record being present in an external Learning Record Store (LRS) using the Experience Api (xAPI / TinCan).

This availability condition makes it possible to show modules or sections only when a user
has performed an action that is recorded in an external LRS, which could have been put there
by an external system.

xAPI requires 3 parameters to look up a record: the `verb`, the `activity` and the `actor`. These represent the statement _The user (actor) performed (verb) in (activity)_.

The **verb** is usually a URL which may look like `http://adlnet.gov/expapi/verbs/experienced` or `http://id.tincanapi.com/verb/replied`. You can list and read about existing verbs here: https://registry.tincanapi.com/#home/verbs

The **activity** is usually an URL which may look like `https://some.fakesite.com/1/2/3`. This will be determined by the system that is writing to the LRS. You will need to look this up.

The **actor** is a user. In this plugin this must match a moodle user record in some way. You can currently look up and actor using their *email*, *idnumber* or *username* field. This must match the actor record being stored in your LRS.

Setting up the plugin
---------------------

Use the Moodle plugin installer to put this plugin into the correct location. If you are unable to install plugins automatically, the plugin folder needs to be located at:

```
<your-moodle-root>/availability/condition/xapiactivity
```

The plugin has the following fields you need to set globally:

**LRS Url**: The URL of your LRS, whcih might look like *https://cloud.scorm.com/lrs/57837584*
**API Key**: The username or apikey for your LRS, which might look like *fds789dhj275hk4jf987*
**API Secret**: The password or secret key for your LRS, which might look like *DUY%H#B@VD%#*
**Authenication Method**: Only basic authentication is supported at this time. Most LRS's support BASIC auth.
**Actor Lookup Field**: The field to match in the moodle user table to determine the actor

Using the plugin
----------------

Once configured with your LRS and authentication details, you can use the plugin anywhere you can normally add a restriction, with the exceptions:

1. The front page of the site
2. The first section/topic of a course

To set up the restiction, use the usual activity conditions editor. You can read more about this here : https://docs.moodle.org/en/Conditional_activities_settings

1. Click the *Add restriction* button
2. Click the *xAPI lookup* button
3. Fill in the *Verb*, *Activity Url* and *Condition Label* fields. The Condition Label is the name or description of the activity that you want to show to users in Moodle. It doesn't have to be the same as the acvitity name in your LRS.

Licence
-------
GPL3