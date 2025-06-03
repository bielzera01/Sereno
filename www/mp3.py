import mercadopago

#def gerar_link_pagamento():
    # Inicializar o SDK com o Access Token da sua conta
sdk = mercadopago.SDK("TEST-6721267685884266-101708-654c2622446ffb96949eec2f2ec9d753-1272168356")
# Criar uma preferência de pagamento
preference_data = {
    "items": [
        {
            "title": "Sessão de Terapia - Pedro Ivankov",
            "quantity": 1,
            "unit_price": 40.00,
            "currency_id": "BRL"
        }
    ],
    "payer": {
        "email": "paciente@email.com"#email do paciente
    },
    "payment_methods": {
        "excluded_payment_types": [
            {"id": "ticket"}
        ],
        "installments": 1
    },
    "back_urls": {
        "success": "http://127.0.0.1:5500/index.html",#URL de pagamento conluido
        "failure": "http://127.0.0.1:5500/index.html",#URL de pagamento falho
        "pending": "http://127.0.0.1:5500/index.html"#URL de pagamento pendente
    },
    "auto_return": "all",
    "external_reference": None,  # ID da transação na sua plataforma
    #"collector_id": 6721267685884266,  # ID da sua plataforma
    "marketplace_fee": 10.00,  # Comissão da plataforma
    "disbursements": [
        {
            "amount": 10,  # Valor para o psicólogo
            "collector_id": 2101030335
        }
    ]
}

# Criar a preferência no Mercado Pago
preference_response = sdk.preference().create(preference_data)

# Recuperar o URL para o checkout
preference_url = preference_response["response"]
link_iniciar_pagamento = preference_url['init_point']
print(f"URL do pagamento: {preference_url}")
#return link_iniciar_pagamento