/// <reference types="Cypress" />

context('Actions', () => {
    beforeEach(() => {
        cy.visit('http://127.0.0.1:8000/')
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
