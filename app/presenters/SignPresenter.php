<?php

namespace App\Presenters;

use Nette,
    App\Model;


/**
 * Sign in/out presenters.
 */
class SignPresenter extends BasePresenter
{


    /**
     * Sign-in form factory.
     * @return Nette\Application\UI\Form
     */
    protected function createComponentSignInForm()
    {
        $form = new Nette\Application\UI\Form;
        $form->addText('username', 'Username:')
            ->setRequired('Aké je vaše prihlasovacie meno?');

        $form->addPassword('password', 'Password:')
            ->setRequired('Vložt vaše heslo.');

        $form->addCheckbox('remember', 'Zostať prihlásený');

        $form->addSubmit('send', 'Prihlásiť!');

        // call method signInFormSucceeded() on success
        $form->onSuccess[] = $this->signInFormSucceeded;
        return $form;
    }


    public function signInFormSucceeded($form, $values)
    {
        if ($values->remember) {
            $this->getUser()->setExpiration('14 days', FALSE);
        } else {
            $this->getUser()->setExpiration('20 minutes', TRUE);
        }

        try {
            $this->getUser()->login($values->username, $values->password);
            $this->flashMessage("Přihlášení proběhlo úspěšně!", 'confirm');
            $this->redirect('Homepage:');

        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError($e->getMessage());
        }
    }

    public function actionOut()
    {
        $this->getUser()->logout();
        $this->flashMessage('You have been signed out.');
        $this->redirect('in');
    }
    
    public function createComponentRegisterForm()
    {
        $form = new Nette\Application\UI\Form;
        $form->addText('name', 'Username:')
            ->setRequired('Please enter your username.');
            
        $form->addText('email', 'Email:')
                ->setRequired('Please enter your email.');            

        $form->addPassword('password', 'Password:')
            ->setRequired('Please enter your password.'); 
            
        $countries = array(
            'Europe' => array(
                'CZ' => 'Česká Republika',
                'SK' => 'Slovensko',
                'GB' => 'Velká Británie',
            ),
            'CA' => 'Kanada',
            'US' => 'USA',
            '?'  => 'jiná',
        );

        $form->addSelect('country', 'Země:', $countries)
            ->setPrompt('Zvolte zemi');            
            
        $form->addSubmit('send', 'Register');            
            
        $form->onSuccess[] = $this->RegisterFormSucceeded;
        return $form;                   
    }
    
    public function RegisterFormSucceeded($form)
    {
        $values = $form->getValues();
        /*
        $email = $this->database->table('users')->where('username', $values->name)
                ->fetch();
        if($email) {
            $form->addError('Tento email je již používán.');
        } else {
        */
            $this->user->getAuthenticator()->add($values->name, $values->password, $values->email);
            $this->flashMessage('Uživatel registrován.');
            $this->redirect('Homepage:default');
        //}

    }


}
