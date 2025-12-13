# Release with GitHub App and Branch Rulesets

Normally if you try to make commits via a GitHub Action (GHA) you'll run 
into an issue of the commit failing should you have branch rulesets turned 
on. You can get around this by using a Personal Access Token (PTA) but this 
isn't the most ideal way to do this, and it also forces you to have access 
to bypass these rules via a user account in some way in order for the PTA to 
work. However, rulesets allow you to have bypass rules for GitHub Apps, and 
so the better way to do this would be to have the GitHub App do the work and 
make the commits.

So let's detail how to do this.

## Create a GitHub App for your Organization

* Go to the settings for your Organization
* Scroll down to Developer Settings
* Choose GitHub Apps
* Click the "New GitHub App" button
* Generate a private key for this app after you've got all the settings how 
  you want them
* Save that key somewhere safe

## Install the GitHub App

* Click "Install App" in the left menu
* Click the Install button for your organization

The GitHub App should now appear under the GitHub Apps page in your settings 
under Third-party Access

## Add Organization wide secrets

* Go to Secrets and variables
* Choose Actions
* Create New organization secret
* Make one for your GitHub App's app id
* Make another for your GitHub App's private key

## Add your GitHub App as a bypass to your rulesets

* Go to the repository under which your workflow will run
* Go to the repository settings
* Click rules
* Click rulesets
* Choose the ruleset you want to bypass and edit it
* Add a new bypass and choose the GitHub App
* Save the ruleset

## Modify the workflow

First you'll need to add the following code before the checkout action of 
your workflow.

```yaml
      - name: Generate a token
        id: generate-token
        uses: actions/create-github-app-token@v2
        with:
          app-id: ${{ secrets.YOUR_GITHUB_APP_APP_ID_SECRET_NAME_HERE }}
          private-key: ${{ secrets.YOUR_GITHUB_APP_PRIVATE_KEY_SECRET_NAME_HERE }}
```

You'll then need to use this generated token in the checkout action:

```yaml
      - name: Checkout repository
        uses: actions/checkout@v6
        with:
          token: ${{ steps.generate-token.outputs.token }}
```

That's it! Enjoy :)
