/// <reference types="Cypress" />

context('Actions', () => {
    beforeEach(() => {
        cy.visit('/')

        cy.get('form[action="/login_check"]').then(($form) => {

            cy.get('#username')
                .type('admin', {force: true})

            cy.get('#password')
                .type('test', {force: true})

            cy.get('form[action="/login_check"]').submit()
        })
    })

    it('create an user', () => {
        cy.get('a[href="/users/create"]').click()

        cy.get('#user_username')
            .type('usertest')
            .should('have.value', 'usertest');

        cy.get('#user_password_first')
            .type('test');

        cy.get('#user_password_second')
            .type('test');

        cy.get('#user_email')
            .type('usertest@test.com');

        cy.get('form[name="user"]')
            .submit()

        cy.get('.alert-success')
    })
})
