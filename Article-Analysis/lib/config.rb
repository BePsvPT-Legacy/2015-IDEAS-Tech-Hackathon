require 'dotenv'

Dotenv.load

DB_HOST = ENV['DB_HOST'] || 'localhost'
DB_DB = ENV['DB_DB'] || 'database'
DB_USER = ENV['DB_USER'] || 'user'
DB_PASS = ENV['DB_PASS'] || 'password'
