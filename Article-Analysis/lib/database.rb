require 'sequel'

require_relative 'config'

DB = Sequel.connect(adapter: 'mysql2', host: DB_HOST, database: DB_DB, user: DB_USER, password: DB_PASS)

Lotteries = DB[:lotteries]

LotteryMethods = DB[:lottery_methods]
