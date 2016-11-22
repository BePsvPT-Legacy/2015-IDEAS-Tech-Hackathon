#!/usr/bin/env ruby
# encoding: UTF-8

require 'bundler/setup'
require 'pp'

require_relative 'database'

def grade?(content)
  !(content =~ /按讚/).nil?
end

def share?(content)
  !(content =~ /分享/).nil?
end

def comment?(content)
  !(content =~ /留言/ || content =~ /留下/ || content =~ /標記/).nil?
end

def find_date(content)
  regexs = [
    %r{.*(?:(?:\d+(?:/|-))?\d+(?:/|月|-)\d+).*(?:～|~|-|至).*((?:\d+(?:/|-))?\d+(?:/|月|-)\d+).*}m,
    %r{.*日.*(?:～|~|-|至).*((?:\d+(?:/|-))?\d+(?:/|月|-)\d+).*}m,
    %r{.*((?:\d+(?:/|-))?\d+(?:/|月|-)\d+).*(?:止|前).*}m,
  ]
  regexs.each do |regex|
    m = content.match(regex)
    return m if m
  end
  nil
end

year = Date.today.year

Lotteries.select(:id, :content)
  .where(Sequel.like(:content, '%抽%'))
  .each do |data|
  id = data[:id]
  content = data[:content]
  metd = 1
  metd += 4 if grade? content
  metd += 2 if share? content
  metd += 1 if comment? content
  date_m = find_date(content)
  if date_m
    m = date_m[1].match(%r{.*(\d+).*(\d+).*$})
    begin
      Lotteries.where(id: id).update(lottery_method_id: metd, expired_at: DateTime.new(year, m[1].to_i, m[2].to_i))
    rescue StandardError
      puts "Error at id: #{id}"
    end
  else
    Lotteries.where(id: id).update(lottery_method_id: metd)
  end
end
